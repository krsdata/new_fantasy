<?php
namespace Modules\Admin\Http\Controllers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests;
use Illuminate\Http\Request;
use Modules\Admin\Http\Requests\CategoryRequest;
use Modules\Admin\Models\User;
use Input, Validator, Auth, Paginate, Grids, HTML;
use Form, Hash, View, URL, Lang, Session, DB;
use Route, Crypt, Str;
use App\Http\Controllers\Controller;
use Illuminate\Http\Dispatcher;
use App\Helpers\Helper;
use Modules\Admin\Models\Roles;
use Modules\Admin\Models\Menu; 
use Modules\Admin\Models\Wallets;
use App\Models\Competition;
use App\Models\TeamA;
use App\Models\TeamB;
use App\Models\Toss;
use App\Models\Venue;
use App\Models\Matches;
use App\Models\Player;
use App\Models\TeamASquad;
use App\Models\TeamBSquad;
use App\Models\CreateContest;
use App\Models\CreateTeam;
use App\Models\Wallet;
use App\Models\JoinContest;
use App\Models\WalletTransaction;
use App\Models\MatchPoint;
use App\Models\MatchStat;
use App\Models\ReferralCode;
use File;
use Modules\Admin\Models\TempMatch;
/**
 * Class MenuController
 */
class FlashMatchController extends Controller {
    /**
     * @var  Repository
     */

    /**
     * Displays all admin.
     *
     * @return \Illuminate\View\View
     */
    public $token;
    public function __construct() {
        $this->middleware('admin');
        View::share('viewPage', 'Wallets');
        View::share('sub_page_title', 'Wallets');
        View::share('helper',new Helper);
        View::share('heading','Wallets');
        View::share('route_url',route('flashMatch'));
        $this->token = env('CRIC_API_KEY',"8740931958a5c24fed8b66c7609c1c49");
        
        $this->record_per_page = 10;// Config::get('app.record_per_page');
        
    } 
    /*
     * Dashboard
     * */

    public function index(Wallets $wallets, Request $request)
    {
        $page_title = 'Wallets';
        $sub_page_title = 'Wallets';
        $page_action = 'View Wallets'; 

        if($request->status!=null && $request->match_id!=null){
            $status = $request->status;
            if($status==1){
                $status_str = "Upcoming";
            }elseif($status==2){
                $status_str = "Completed";
            }elseif($status==3){
                $status_str = "Live";
            }else{
                $status_str = "Cancelled";
            }
            \DB::table('matches')->where('match_id',$request->match_id)
                        ->update(
                            [
                                 'status'  => $request->status,
                                'status_str' => $status_str
                            ]
                        );
           return Redirect::to(route('flashMatch'))
                            ->with('flash_alert_notice', 'Match status marked as '.$status_str);       
        }
        
        $total_flash_match = Matches::where('upload_type','manual')->count();
        $total_live_match = Matches::where('upload_type','manual')->where('status',3)->count();
        $total_completed_match = Matches::where('upload_type','manual')->where('status',2)->count();
        $total_upcoming_match = Matches::where('upload_type','manual')->where('status',1)->count();

        $total_cancel_match = Matches::where('upload_type','manual')->where('status',4)->count(); 

        return view('packages::flashMatch.index', compact('total_flash_match', 'page_title', 'page_action','sub_page_title','total_upcoming_match','total_completed_match','total_live_match','total_cancel_match'));
    } 
    /*
     * create Group method
     * */

    public function create(Wallets $wallets)
    {

        $page_title     = 'Wallets';
        $page_action    = 'Create Wallets';
        $table_cname = \Schema::getColumnListing('wallets');
        $except = ['id','created_at','updated_at'];
        $data = [];
        foreach ($table_cname as $key => $value) {

           if(in_array($value, $except )){
                continue;
           }
             $tables[] = $value;
        }

        return view('packages::flashMatch.create', compact('wallets', 'page_title', 'page_action','tables'));
    }

    /*
     * Save Group method
     * */

    public function store(Request $request, Wallets $wallets)
    {
        $validator = Validator::make($request->all(), [
            'match_json'    => 'mimes:json,txt',
            'player_json'   => 'mimes:json,txt',
            'point_json'    => 'mimes:json,txt'
        ]);

        // Return Error Message
        if ($validator->fails()) {
            $error_msg  =   [];
            foreach ( $validator->messages()->all() as $key => $value) {
                array_push($error_msg, $value);
            }
            return Redirect::to(route('flashMatch'))
                            ->with('flash_alert_notice', 'File must be json or text type'); 
        } 

        if ($request->file('match_json')) {
            $file = file_get_contents($request->match_json);
            
           $status =  $this->saveMatchDataFromJson($file);
           if( $status ){
                $msg = " Match info is uploaded";
           }else{
                 $msg = " Match info contain invalid key";
           }
           return Redirect::to(route('flashMatch'))
                            ->with('flash_alert_notice',$msg); 
        }
        elseif ($request->file('player_json')) {
            $file = file_get_contents($request->player_json);
            $player_json = json_decode($file); 
            $rs = $this->getSquad($player_json);
            if( $rs){
                $msg = " Player info is uploaded";
           }else{
                 $msg = " Player info contain invalid key";
           }
            return Redirect::to(route('flashMatch'))
                            ->with('flash_alert_notice', $msg);
        }
        elseif ($request->file('point_json')) {

           $file = file_get_contents($request->point_json);
           $status =  $this->savePointsFromJson($file);
           if( $status ){
                $msg = " Match player points is uploaded";
           }else{
                 $msg = " File contain invalid key";
           }
           return Redirect::to(route('flashMatch'))
                            ->with('flash_alert_notice', $msg);
        }
        elseif ($request->file('playing11_json')) {
            $file = file_get_contents($request->playing11_json);
            $playing11_json = json_decode($file); 
            $rs = $this->getPlayingSquad($playing11_json);
            if( $rs){
                $msg = " Playing 11 info is uploaded";
            }else{
                 $msg = " Playing11 info contain invalid key";
            }
            return Redirect::to(route('flashMatch'))
                            ->with('flash_alert_notice', $msg);
        }
        else{
             return Redirect::to(route('flashMatch'))
                            ->with('flash_alert_notice', 'No file selected');
        }
    }

    /*
     * Edit Group method
     * @param
     * object : $menu
     * */

    public function edit($id) { 

        return view('packages::flashMatch.edit', compact( 'wallets', 'page_title','page_action', 'tables'));
    }

    public function update(Request $request, $id) {
        return Redirect::to(route('flashMatch'))
                        ->with('flash_alert_notice', ' Wallets  successfully updated.');
    }
    /*
     * Delete User
     * @param ID
     *
     */
    public function destroy($id) {
        return Redirect::to(route('flashMatch'))
                        ->with('flash_alert_notice', ' wallets  successfully deleted.');
    }
    // save Match
    public function saveMatchDataFromJson($data)
    {
        $data = json_decode($data);
        if(isset($data->response) && isset($data->response->format)){
            
            $result_set = $data->response;
            $mid = [];
            //  foreach ($results as $key => $result_set) {

            if($result_set->format==5   or $result_set->format==17){
                // continue;
            }
            foreach ($result_set as $key => $rs) {
                $data_set[$key] = $rs;
            }

            $competition = Competition::firstOrNew(['match_id' => $data_set['match_id']]);
            $competition->match_id   = $data_set['match_id'];

            foreach ($data_set['competition'] as $key => $value) {
                $competition->$key = $value;
            }
            $competition->save();
            $competition_id = $competition->id;

            /*TEAM A*/
            $team_a = TeamA::firstOrNew(['match_id' => $data_set['match_id']]);
            $team_a->match_id   = $data_set['match_id'];

            foreach ($data_set['teama'] as $key => $value) {
                $team_a->$key = $value;
            }

            $team_a->save();

            $team_a_id = $team_a->id;


            /*TEAM B*/
            $team_b = TeamB::firstOrNew(['match_id' => $data_set['match_id']]);
            $team_b->match_id   = $data_set['match_id'];

            foreach ($data_set['teamb'] as $key => $value) {
                $team_b->$key = $value;
            }

            $team_b->save();
            $team_b_id = $team_b->id;


            /*Venue */
            $venue = Venue::firstOrNew(['match_id' => $data_set['match_id']]);
            $venue->match_id   = $data_set['match_id'];

            foreach ($data_set['venue'] as $key => $value) {
                $venue->$key = $value;
            }

            $venue->save();
            $venue_id = $venue->id;


            /*Venue */
            $toss = Toss::firstOrNew(['match_id' => $data_set['match_id']]);
            $toss->match_id   = $data_set['match_id'];

            foreach ($data_set['toss'] as $key => $value) {
                $toss->$key = $value;
            }

            $toss->save();
            $toss_id = $toss->id;

            $remove_data = ['toss','venue','teama','teamb','competition','points'];

            $matches = Matches::firstOrNew(['match_id' => $data_set['match_id']]);

            foreach ($data_set as $key => $value) {

                if(in_array($key, $remove_data)){
                    continue;
                }
                $matches->$key = $value;

            }
            $matches->toss_id   = $toss_id;
            $matches->venue_id  = $venue_id;
            $matches->teama_id  = $team_a_id;
            $matches->teamb_id  = $team_b_id;
            $matches->competition_id = $toss_id;
           // $matches->upload_type = 'manual';
            
            $matches->save();

            if(isset($result_set->points)){
                $m = [];
                foreach ($result_set->points as $team => $teams) {
                    if($teams==""){
                        continue;
                    }
                    foreach ($teams as $key => $players) {
                        foreach ($players as $key => $result) {
                            $result->match_id = $match->match_id;
                            if($result->pid==null){
                                continue;
                            }
                            $m[] = MatchPoint::updateOrCreate(
                                ['match_id'=>$match->match_id,'pid'=>$result->pid],
                                (array)$result);

                        }
                }
            }
            }else{
              $this->createContest($data_set['match_id']);  
            } 

            return true;
        }else{
            return false;
        }
    }
    // save Match
    public function savePointsFromJson($data)
    {
        $data = json_decode($data);
        if(isset($data->response) && isset($data->response->match_id)){
           
            $match_id = $data->response->match_id;
            $result_set = $data->response; 

            if(isset($result_set->points)){
                $m = [];
                foreach ($result_set->points as $team => $teams) {
                    if($teams==""){
                        continue;
                    }
                        foreach ($teams as $key => $players) {
                            foreach ($players as $key => $result) {
                                $result->match_id = $match_id;
                                if($result->pid==null){
                                    continue;
                                }
                                $m[] = MatchPoint::updateOrCreate(
                                    ['match_id'=>$match_id,'pid'=>$result->pid],
                                    (array)$result);
                                
                            }
                    }
                }
                return true;
            }else{
              return false;
            } 

            return true;
        }else{
            return false;
        }
    }
     // crrate contest dyanamic
    public function createContest($match_id=null){

        $default_contest = \DB::table('default_contents')
            ->whereNull('match_id')
            ->get();

        foreach ($default_contest as $key => $result) {
            $createContest = CreateContest::firstOrNew(
                [
                    'match_id'          =>  $match_id,
                    'contest_type'      =>  $result->contest_type,
                    'entry_fees'        =>  $result->entry_fees,
                    'total_spots'       =>  $result->total_spots,
                    'first_prize'       =>  $result->first_prize

                ]
            );

            $createContest->match_id            =   $match_id;
            $createContest->contest_type        =   $result->contest_type;
            $createContest->total_winning_prize =   $result->total_winning_prize;
            $createContest->entry_fees          =   $result->entry_fees;
            $createContest->total_spots         =   $result->total_spots;
            $createContest->first_prize         =   $result->first_prize;
            $createContest->winner_percentage   =   $result->winner_percentage;
            $createContest->cancellation        =   $result->cancellation;
            $createContest->default_contest_id  =   $result->id;
            $createContest->save();

            $default_contest_id = \DB::table('default_contents')
                ->where('match_id',$match_id)
                ->get();

            if($default_contest_id){
                foreach ($default_contest_id as $key => $value) {
                    $this->updateContestByMatch($match_id);
                }
            }
        }
    }
    /*Update Contest*/
    public function updateContestByMatch($match_id=null){

        $default_contest = \DB::table('default_contents')
            ->where('match_id',$match_id) 
            ->get();

        foreach ($default_contest as $key => $result) {
            $createContest = CreateContest::firstOrNew(
                [
                    'match_id'          =>  $match_id,
                    'contest_type'      =>  $result->contest_type,
                    'entry_fees'        =>  $result->entry_fees,
                    'total_spots'       =>  $result->total_spots,
                    'first_prize'       =>  $result->first_prize        
                ]
            );

            $createContest->match_id            =   $match_id;
            $createContest->contest_type        =   $result->contest_type;
            $createContest->total_winning_prize =   $result->total_winning_prize;
            $createContest->entry_fees          =   $result->entry_fees;
            $createContest->total_spots         =   $result->total_spots;
            $createContest->first_prize         =   $result->first_prize;
            $createContest->winner_percentage   =   $result->winner_percentage;
            $createContest->cancellation        =   $result->cancellation;
            $createContest->default_contest_id  =   $result->id;
            $createContest->save();
            return true;
        }
    }
    /*Playing 11 squad and notification*/
    public function getPlayingSquad($data=null)
    {  
        $match_id = $data->response->match_id; 
        if(isset($data->response) && isset($data->response->match_id))
        {
            $teama = $data->response->teama;
            foreach ($teama->squads as $key => $squads) {
                $teama_obj = TeamASquad::firstOrNew(
                    [
                        'team_id'=>$teama->team_id,
                        'player_id'=>$squads->player_id,
                        'match_id'=>$match_id
                    ]
                );

                $teama_obj->team_id   =  $teama->team_id;
                $teama_obj->player_id =  $squads->player_id;
                $teama_obj->role      =  $squads->role;
                $teama_obj->role_str  =  $squads->role_str;
                $teama_obj->playing11 =  $squads->playing11;
                $teama_obj->name      =  $squads->name;
                $teama_obj->match_id  =  $match_id;

                $teama_obj->save();
                $team_id[$squads->player_id] = $teama->team_id;
            }
            $teamb = $data->response->teamb;
            foreach ($teamb->squads as $key => $squads) {

                $teamb_obj = TeamBSquad::firstOrNew(['team_id'=>$teamb->team_id,'player_id'=>$squads->player_id,'match_id'=>$match_id]);

                $teamb_obj->team_id   =  $teamb->team_id;
                $teamb_obj->player_id =  $squads->player_id;
                $teamb_obj->role      =  $squads->role;
                $teamb_obj->role_str  =  $squads->role_str;
                $teamb_obj->playing11 =  $squads->playing11;
                $teamb_obj->name      =  $squads->name;
                $teamb_obj->match_id  =  $match_id;
                $teamb_obj->save();

                $team_id[$squads->player_id] = $teamb->team_id;
            }

            $join_contest = JoinContest::whereHas('user')->where('match_id',$match_id)
                        ->get()
                        ->transform(function($item,$key){
                        
                        $device_token = $item->user->device_id??false;
                        if($device_token){
                           $data = [
                                    'action' => 'notify' ,
                                    'title' => 'Playing 11 announced' ,
                                    'message' => 'Dear user, Please update your team.'
                                ];
                            Helper::sendMobileNotification($device_token,$data); 
                        }
                });

            return true;
            
        }else{
            return false;
        }    
    }
    /*Save player info*/
    public function getSquad($data=null){  
        $match_id = $data->response->match_id;

        if(isset($data->response) && isset($data->response->match_id))
        {   
            $teama = $data->response->teama;
            foreach ($teama->squads as $key => $squads) {
                $teama_obj = TeamASquad::firstOrNew(
                    [
                        'team_id'   => $teama->team_id,
                        'player_id' => $squads->player_id,
                        'match_id'  => $match_id
                    ]
                );

                $teama_obj->team_id   =  $teama->team_id;
                $teama_obj->player_id =  $squads->player_id;
                $teama_obj->role      =  strtolower($squads->role);
                $teama_obj->role_str  =  $squads->role_str;
                $teama_obj->playing11 =  $squads->playing11;
                $teama_obj->name      =  $squads->name;
                $teama_obj->match_id  =  $match_id;

                $teama_obj->save();
                $team_id[$squads->player_id] = $teama->team_id;

                 // player info
                $player_data =   Player::firstOrNew(
                    [
                        'pid'       =>  $squads->player_id,
                        'team_id'   =>  $teama->team_id,
                        'match_id'  =>  $match_id
                    ]
                );
                
                $name = explode(" ", $squads->name);
                $player_data->match_id  = $match_id;
                $player_data->pid       = $squads->player_id;
                $player_data->team_id   = $teama->team_id;
                $player_data->title     = $squads->name;
                $player_data->short_name= $squads->name;
                $player_data->first_name= $name[0]??null;
                $player_data->last_name = $name[1]??null;
                $player_data->country   = "in";
                $player_data->playing_role   =  strtolower($squads->role);
                
                $player_data->save();

                // Match points
                $data_mp =  MatchPoint::firstOrNew(
                    [
                        'pid'=>$squads->player_id,
                        'match_id'=>$match_id
                    ]
                ); 
                if($data_mp->short_name==null){
                    $data_mp->match_id  =  $match_id;
                    $data_mp->pid = $squads->player_id; 
                    $data_mp->role = strtolower($squads->role); 
                    $data_mp->name = $squads->name; 
                    $data_mp->rating = 0;
                
                    $data_mp->save(); 
                }

            }
            $teamb = $data->response->teamb;
            foreach ($teamb->squads as $key => $squads) {

                $teamb_obj = TeamBSquad::firstOrNew(['team_id'=>$teamb->team_id,'player_id'=>$squads->player_id,'match_id'=>$match_id]);

                $teamb_obj->team_id   =  $teamb->team_id;
                $teamb_obj->player_id =  $squads->player_id;
                $teamb_obj->role      =  strtolower($squads->role);
                $teamb_obj->role_str  =  $squads->role_str;
                $teamb_obj->playing11 =  $squads->playing11;
                $teamb_obj->name      =  $squads->name;
                $teamb_obj->match_id  =  $match_id;
                $teamb_obj->save();     

                $team_id[$squads->player_id] = $teamb->team_id;


                $data_mp =  MatchPoint::firstOrNew(
                    [
                        'pid'=>$squads->player_id,
                        'match_id'=>$match_id
                    ]
                ); 
                if($data_mp->short_name==null){
                    $data_mp->match_id  =  $match_id;
                    $data_mp->pid = $squads->player_id; 
                    $data_mp->role = strtolower($squads->role); 
                    $data_mp->name = $squads->name; 
                    $data_mp->rating = 0;
                
                    $data_mp->save(); 
                }

                // player info
                $player_data =   Player::firstOrNew(
                    [
                        'pid'       =>  $squads->player_id,
                        'team_id'   =>  $teamb->team_id,
                        'match_id'  =>  $match_id
                    ]
                );
                
                $name = explode(" ", $squads->name);
                $player_data->match_id  = $match_id;
                $player_data->pid       = $squads->player_id;
                $player_data->team_id   = $teamb->team_id;
                $player_data->title     = $squads->name;
                $player_data->short_name= $squads->name;
                $player_data->first_name= $name[0]??null;
                $player_data->last_name = $name[1]??null;
                $player_data->country   = "in";
                $player_data->playing_role   =  strtolower($squads->role);
                
                $player_data->save();

            }                           
            // update all players
            if(isset($data->response->players)){
                foreach ($data->response->players as $pkey => $pvalue)
                {                           

                $data_set =   Player::firstOrNew(
                    [
                        'pid'=>$pvalue->pid,
                        'team_id'=>$team_id[$pvalue->pid],
                        'match_id'=>$match_id
                    ]
                );
                foreach ($pvalue as $key => $value) {
                    if($key=="primary_team"){
                        continue;
                        $data_set->$key = json_encode($value);
                    }
                    $data_set->$key      =  $value;
                    $data_set->match_id  =  $match_id;
                    $data_set->pid       = $pvalue->pid;
                    $data_set->team_id   = $team_id[$pvalue->pid];
                }
                $data_set->save();
                }       
           
            
            // update player in updatepoint table

                foreach ($data->response->players as $pkey => $pvalue)
                {
                    $data_mp =  MatchPoint::firstOrNew(
                        [
                            'pid'=>$pvalue->pid,
                            'match_id'=>$match_id
                        ]
                    ); 
                    if($data_mp->short_name==null){
                        $data_mp->match_id  =  $match_id;
                        $data_mp->pid = $pvalue->pid; 
                        $data_mp->role = strtolower($pvalue->playing_role); 
                        $data_mp->name = $pvalue->short_name; 
                        $data_mp->rating = $pvalue->fantasy_player_rating;
                    
                        $data_mp->save(); 
                    } 
                }
            }
            return true;
        }else{
            return false;
        }    
    }

    public function oldMatch(TempMatch $match, Request $request) 
    {  
        $page_title = 'TempMatch';
        $sub_page_title = 'View TempMatch';
        $page_action = 'View TempMatch'; 

        if($request->match_id && (($request->date_start && $request->date_end) || $request->status)){
            if($request->date_end && $request->date_start){
                $date_start = \Carbon\Carbon::createFromFormat('Y-m-d H:i',$request->date_start)
                ->setTimezone('UTC')
                ->format('Y-m-d H:i');

                $date_end = \Carbon\Carbon::createFromFormat('Y-m-d H:i', $request->date_end)
                    ->setTimezone('UTC')
                    ->format('Y-m-d H:i'); 
                $timestamp_start = strtotime($date_start);
                $timestamp_end   = strtotime($date_end);
            }
            

            $status = $request->status;
            if($status==1){
                $status_str = "Upcoming";
            }elseif($status==2){
                $status_str = "Completed";
            }elseif($status==3){
                $status_str = "Live";
            }else{
                //$status_str = "Cancelled";
            }
            if($request->match_id && $request->date_end && $request->date_start && $request->change_date){
                $data =   [
                                'timestamp_start' => $timestamp_start,
                                'timestamp_end' => $timestamp_end,
                                'date_start'  => $date_start,
                                'date_end'  => $date_end 
                          ];  
            }

            if($request->match_id && $request->status && $request->change_status){
                $data =    [
                            'status'  => $request->status,
                            'status_str' => $status_str
                        ];   
            }
            
        //    \DB::table('matches')->where('match_id',$request->match_id)
                //        ->update($data);

                     
          //  return Redirect::to(route('match'))->with('flash_alert_notice', 'Match updated successfully!');  

        }

        // Search by name ,email and group
        $search = Input::get('search');
        $status = Input::get('status');

        $date_start = $request->get('start_date');
        $end_date = $request->get('end_date');
        if ($search || $status || $date_start || $end_date) {
             
            $search = isset($search) ? Input::get('search') : '';
               
            $match = TempMatch::where(function($query) use($search,$status,$date_start,$end_date) {    
                        if($date_start && $end_date){
                           $query->Where('date_start', '>=', $date_start); 
                           $query->Where('date_end', '<=', $end_date);
                        }
                        if($date_start){
                          // $query->orWhere('date_start', '=', $date_start);
                        }
                        if($end_date){
                          // $query->orWhere('date_end', '=', $end_date);
                        }
                        if (!empty($status)) {
                            $query->Where('status', '=', $status);
                            if($status==1){
                                $query->where('timestamp_start','>=',time());
                            }
                             if($status==2){
                                $query->orderBy('timestamp_start','DESC');
                            }
                        }
                        if (!empty($search)) {
                            $query->orWhere('title', 'LIKE', "%$search%");
                        }
                        if (!empty($search)) {
                            $query->orWhere('match_id', 'LIKE', "%$search%");
                        }
                        if (!empty($search)) {
                            $query->orWhere('short_title', 'LIKE', "%$search%");
                        } 
                    })->orderBy('updated_at','DESC')->Paginate($this->record_per_page); 
             
        } else {
            $match = TempMatch::orderBy('updated_at','DESC')->Paginate($this->record_per_page);
        } 

        $total_match = TempMatch::count();

        return view('packages::flashMatch.tempMatch', compact('match','page_title', 'page_action','sub_page_title','total_match'));

    }
    //upate match from api 

    public function updateMatchDataByStatus(Request  $request)
    {   
        $status     = $request->status??2;
        $token      = $this->token;
        $per_page   = $request->record_per_page??10;
        $date       = date('Y-m-d'); // 2019-03-23_2019-05-12 

        if($request->date_start && $request->date_end){

            $date_start = \Carbon\Carbon::parse($request->date_start)->setTimezone('UTC')->format('Y-m-d');
            $date_end = \Carbon\Carbon::parse($request->date_end)->setTimezone('UTC')->format('Y-m-d');

            $date   = $date_start.'_'.$date_end; 
        }  
        $paged      = $request->paged??1;
        $format     = $request->format??6;

        $data =    file_get_contents('https://rest.entitysport.com/v2/matches/?status='.$status.'&token='.$token.'&per_page='.$per_page.'&date='.$date.'&format='.$format.'&paged='.$paged);
        
     //   echo 'https://rest.entitysport.com/v2/matches/?status='.$status.'&token='.$token.'&per_page='.$per_page.'&date='.$date.'&format='.$format.'&paged='.$paged;
        $page_title = 'Match';
        $sub_page_title = 'View Match';
        $page_action = 'View Match'; 
        $match = json_decode($data);
        $total_match = count($match->response->items);
        if(!isset($match->response->items) || count($match->response->items)==0){
            return Redirect::to('admin/oldMatch')->with('flash_alert_notice', 'No Match Found');  
        }

        $match = $match->response->items;

        

        foreach ($match as $key => $results) {

            $tm = TempMatch::where(
                [
                    'match_id'=>$results->match_id
                ]
            )->first();

            if($tm){

            }else{
              $tm =  new TempMatch;  
            }

            foreach ($results as $key => $result) {
                if($key=='competition'){ 
                    $tm->competition_id = json_encode((array)$result);
                }elseif($key=='teama'){
                    $tm->teama_id =json_encode((array)$result);
                }
                elseif($key=='teamb'){
                    $tm->teamb_id = json_encode((array)$result);
                }
                elseif($key=='venue'){
                    $tm->venue_id = json_encode((array)$result);
                }elseif($key=='toss'){
                    $tm->toss_id = json_encode((array)$result);
                }
                else{
                    $tm->$key = $result;
                }
            } 
            $tm->save();  
        }
       return Redirect::to('admin/oldMatch')->with('flash_alert_notice', $total_match.' Match updated');
         

        
        $rs = $this->saveMatchDataFromAPI($data);

        if($rs){
            return Redirect::to(route('flashMatch'))
                            ->with('flash_alert_notice', 'Total match uploaded '.$rs);
        }else{
            return Redirect::to(route('flashMatch'))
                            ->with('flash_alert_notice', 'No match found');
        }
        return [$fileName.' match data updated successfully'];
    }

    public function saveMatchDataFromAPI($data){
        $data = json_decode($data);

        if(isset($data->response) && count($data->response->items)){

            $results = $data->response->items;
            $mid = [];
            foreach ($results as $key => $result_set) {
                if($result_set->format==5   or $result_set->format==17){
                 //   continue;
                }
                foreach ($result_set as $key => $rs) {
                    $data_set[$key] = $rs;
                }
                $competition = Competition::firstOrNew(['match_id' => $data_set['match_id']]);
                $competition->match_id   = $data_set['match_id'];

                foreach ($data_set['competition'] as $key => $value) {
                    $competition->$key = $value;
                }
                $competition->save();
                $competition_id = $competition->id;

                /*TEAM A*/
                $team_a = TeamA::firstOrNew(['match_id' => $data_set['match_id']]);
                $team_a->match_id   = $data_set['match_id'];

                foreach ($data_set['teama'] as $key => $value) {
                    $team_a->$key = $value;
                }
                $team_a->save();
                $team_a_id = $team_a->id;
                /*TEAM B*/
                $team_b = TeamB::firstOrNew(['match_id' => $data_set['match_id']]);
                $team_b->match_id   = $data_set['match_id'];

                foreach ($data_set['teamb'] as $key => $value) {
                    $team_b->$key = $value;
                }

                $team_b->save();
                $team_b_id = $team_b->id;


                /*Venue */
                $venue = Venue::firstOrNew(['match_id' => $data_set['match_id']]);
                $venue->match_id   = $data_set['match_id'];

                foreach ($data_set['venue'] as $key => $value) {
                    $venue->$key = $value;
                }

                $venue->save();
                $venue_id = $venue->id;


                /*Venue */
                $toss = Toss::firstOrNew(['match_id' => $data_set['match_id']]);
                $toss->match_id   = $data_set['match_id'];

                foreach ($data_set['toss'] as $key => $value) {
                    $toss->$key = $value;
                }

                $toss->save();
                $toss_id = $toss->id;

                $remove_data = ['toss','venue','teama','teamb','competition'];


                $matches = Matches::firstOrNew(['match_id' => $data_set['match_id']]);

                foreach ($data_set as $key => $value) {

                    if(in_array($key, $remove_data)){
                        continue;
                    }
                    $matches->$key = $value;

                }
                $matches->toss_id = $toss_id;
                $matches->venue_id = $venue_id;
                $matches->teama_id = $team_a_id;
                $matches->teamb_id = $team_b_id;
                $matches->competition_id = $toss_id;
                $matches->upload_type = 'manual';
                $matches->save();

                $mid[] = $data_set['match_id'];
                $this->createContest($data_set['match_id']);
                //
            }
            if(count($mid)){
                $this->saveSquad($mid);
                // $this->saveSquad($mid);
            }
            return count($mid);
        }else{
           return false; 
        }
    }
    //save squad
    public function saveSquad($match_ids=null){

        foreach ($match_ids as $key => $match_id) {
            # code...
            $t1 =  date('h:i:s');
            $token =  $this->token;
            $path = file_get_contents('https://rest.entitysport.com/v2/matches/'.$match_id.'/squads/?token='.$token);
            $data = json_decode($path);

            // update team a players
            $teama = $data->response->teama;
            foreach ($teama->squads as $key => $squads) {
                $teama_obj = TeamASquad::firstOrNew(
                    [
                        'team_id'=>$teama->team_id,
                        'player_id'=>$squads->player_id,
                        'match_id'=>$match_id
                    ]
                );

                $teama_obj->team_id   =  $teama->team_id;
                $teama_obj->player_id =  $squads->player_id;
                $teama_obj->role      =  strtolower($squads->role);
                $teama_obj->role_str  =  $squads->role_str;
                $teama_obj->playing11 =  $squads->playing11;
                $teama_obj->name      =  $squads->name;
                $teama_obj->match_id  =  $match_id;

                $teama_obj->save();
                $team_id[$squads->player_id] = $teama->team_id;
            }

            $teamb = $data->response->teamb;
            foreach ($teamb->squads as $key => $squads) {

                $teamb_obj = TeamBSquad::firstOrNew(['team_id'=>$teamb->team_id,'player_id'=>$squads->player_id,'match_id'=>$match_id]);

                $teamb_obj->team_id   =  $teamb->team_id;
                $teamb_obj->player_id =  $squads->player_id;
                $teamb_obj->role      =  strtolower($squads->role);
                $teamb_obj->role_str  =  $squads->role_str;
                $teamb_obj->playing11 =  $squads->playing11;
                $teamb_obj->name      =  $squads->name;
                $teamb_obj->match_id  =  $match_id;
                $teamb_obj->save();

                $team_id[$squads->player_id] = $teamb->team_id;
            }
            // update all players
            foreach ($data->response->players as $pkey => $pvalue)
            {

                $data_set =   Player::firstOrNew(
                    [
                        'pid'=>$pvalue->pid,
                        'team_id'=>$team_id[$pvalue->pid],
                        'match_id'=>$match_id
                    ]
                );

                foreach ($pvalue as $key => $value) {
                    if($key=="primary_team"){
                        continue;
                        $data_set->$key = json_encode($value);
                    }
                    $data_set->$key  =  $value;
                    $data_set->match_id  =  $match_id;
                    $data_set->pid = $pvalue->pid;
                    $data_set->team_id = $team_id[$pvalue->pid];
                }

                $data_set->save();
            }
            // update player in updatepoint table

            foreach ($data->response->players as $pkey => $pvalue)
            {
                $data_mp =  MatchPoint::firstOrNew(
                    [
                        'pid'=>$pvalue->pid,
                        'match_id'=>$match_id
                    ]
                ); 
                if($data_mp->short_name==null){
                    $data_mp->match_id  =  $match_id;
                    $data_mp->pid = $pvalue->pid; 
                    $data_mp->role = strtolower($pvalue->playing_role); 
                    $data_mp->name = $pvalue->short_name; 
                    $data_mp->rating = $pvalue->fantasy_player_rating;
                
                    $data_mp->save(); 
                } 
            }
            $t2 =  date('h:i:s');
            //echo $t1.'--'.$t2;
        }
    }
}
