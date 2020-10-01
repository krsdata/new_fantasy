<?php
namespace Modules\Admin\Http\Controllers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests;
use Illuminate\Http\Request;
use Modules\Admin\Models\User; 
use Input;
use Validator;
use Auth;
use Paginate;
use Grids;
use HTML;
use Form;
use Hash;
use View;
use URL;
use Lang;
use Session; 
use Route;
use Crypt; 
use App\Http\Controllers\Controller;
use Illuminate\Http\Dispatcher; 
use App\Helpers\Helper;
use Modules\Admin\Models\Users;
use App\Models\Matches as Match;
use App\Models\Wallet;
use App\Models\JoinContest;
use App\Models\WalletTransaction;
use App\Models\CreateContest;
use App\Models\CreateTeam;
use Response; 
/**
 * Class AdminController
 */
class MatchController extends Controller {
    /**
     * @var  Repository
     */

    /**
     * Displays all admin.
     *
     * @return \Illuminate\View\View
     */
    public function __construct(Match $match) { 
        $this->middleware('admin');
        View::share('viewPage', 'Match');
        View::share('sub_page_title', 'Match');
        View::share('helper',new Helper);
        View::share('heading','Match');
        View::share('route_url',route('match')); 
        $this->record_per_page = Config::get('app.record_per_page'); 
    }

    
    /*cancelMatch*/
    public function cancelContest(Request $request){
        $match_id = $request->match_id;
        $contest_id = $request->cancel_contest;

        if($request->cancel_contest){
            $JoinContest = JoinContest::whereHas('user')->with('contest')
                        ->where('match_id',$request->match_id)
                        ->whereIn('contest_id',$request->cancel_contest)
                        ->get()
                        ->transform(function($item,$key){
                        $cancel_contest = CreateContest::find($item->contest_id);
                        if($cancel_contest->usable_bonus){
                            $bonus_amount = $cancel_contest->entry_fees*($cancel_contest->usable_bonus/100);    
                        }else{
                            $bonus_amount = 0;
                        }
                        
                        $amount = $cancel_contest->entry_fees-$bonus_amount;
                        if($item->cancel_contest==0){

                            \DB::beginTransaction();
                            $cancel_contest->is_cancelled = 1;
                            $cancel_contest->save();
                            
                            if(isset($item->contest) && $item->contest->entry_fees){   
                                $transaction_id = $item->match_id.'S'.$item->contest_id.'F'.$item->created_team_id.'R'.$item->user_id;
                                $wt =    WalletTransaction::firstOrNew(
                                        [
                                           'user_id' => $item->user_id,
                                           'transaction_id' => $transaction_id
                                        ]
                                    );
                                $wt->user_id            = $item->user_id;   
                                $wt->amount             = $item->contest->entry_fees;  
                                $wt->payment_type       = 7;  
                                $wt->payment_type_string = "Refunded";
                                $wt->transaction_id     = $transaction_id;
                                $wt->payment_mode       = env('company_name');   
                                $wt->payment_status     = "success";
                                $wt->debit_credit_status = "+"; 
                                $wt->match_id  = $item->match_id;
                                $wt->contest_id =  $item->contest_id;  
                                $wt->save();

                                $wallet = Wallet::firstOrNew(
                                        [
                                           'user_id' => $item->user_id,
                                           'payment_type' => 4
                                        ]
                                    );

                                $wallet->user_id        =  $item->user_id;
                                $wallet->amount = $wallet->amount+$amount;
                                
                                $wallet->save();

                                $wallet2 = Wallet::firstOrNew(
                                        [
                                           'user_id' => $item->user_id,
                                           'payment_type' => 1
                                        ]
                                    );

                                $wallet2->user_id        =  $item->user_id;
                                $wallet2->amount = $wallet2->amount+$bonus_amount;
                                $wallet2->save();

                            }
 
                            \DB::commit();

                            $item->cancel_message = 'Contest Cancelled' ;
                            return $item;
                        }else{
                            $item->cancel_message = 'Already Cancelled' ; 
                            return $item; 
                        }
                    });               
        
        if($JoinContest->count()==0 and count($request->cancel_contest)){
           
            foreach ($request->cancel_contest as $key => $value) {
                $cancel_contest = CreateContest::find($value);
                $cancel_contest->is_cancelled = 1;
                $cancel_contest->save();
            }

           return Redirect::to(route('match'))->with('flash_alert_notice', 'Selected contest is cancelled');

        }

        $match      = Match::where('match_id',$match_id)->first();

        $contest_count    = CreateContest::whereIn('id',$contest_id)->count();
        
        $join_contest_user = JoinContest::where('match_id',$match_id)
                            ->whereIn('contest_id',$contest_id)
                            ->where('cancel_contest',0)
                            ->pluck('user_id')
                            ->toArray();
       
        $device_id  = User::whereIn('id',$join_contest_user)
                        ->pluck('device_id')
                        ->toArray();
       // if contest was joined
        $msg = "$match->title contest has been Cancelled";              
        if(count($join_contest_user)){
            $data = [
                    'action' => 'notify' ,
                    'title' => 'Contest Cancelled and amount refunded' ,
                    'message' => $msg
                ];
               
            $this->sendNotification($device_id, $data);
        } 

        $JoinContest = JoinContest::where('match_id',$request->match_id)
                        ->whereIn('contest_id',$request->cancel_contest)
                        ->get()
                        ->transform(function($item,$key){

                            $cancel_contest = JoinContest::find($item->id);
                            $cancel_contest->cancel_contest=1;
                            $cancel_contest->save(); 
                        });


        return Redirect::to(route('match'))->with('flash_alert_notice', 'Match Contest Cancelled successfully');
        }else{
            return Redirect::to(route('match'))->with('flash_alert_notice', 'No Contest selected for cancellation'); 
        }
    }

     public function sendNotification($token, $data){
     
        $serverLKey = 'AIzaSyAFIO8uE_q7vdcmymsxwmXf-olotQmOCgE';
        $fcmUrl = 'https://fcm.googleapis.com/fcm/send';

       $extraNotificationData = $data;

       if(is_array($token)){
            $fcmNotification = [
               'registration_ids' => $token, //multple token array
              // 'to' => $token, //single token
               //'notification' => $notification,
               'data' => $extraNotificationData
            ];
       }else{
                $fcmNotification = [
               //'registration_ids' => $tokenList, //multple token array
               'to' => $token, //single token
               //'notification' => $notification,
               'data' => $extraNotificationData
            ];
        }
       

       $headers = [
           'Authorization: key='.$serverLKey,
           'Content-Type: application/json'
       ];


       $ch = curl_init();
       curl_setopt($ch, CURLOPT_URL, $fcmUrl);
       curl_setopt($ch, CURLOPT_POST, true);
       curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
       curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
       curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
       $result = curl_exec($ch);
       //echo "result".$result;
       //die;
       curl_close($ch);
       return true;
    }
    /*cancelMatch*/
    public function cancelMatch(Request $request){
        return false;
        $match_id = $request->match_id;
        if($request->match_id){
            $data['status']         = 4;
            $data['status_str']     = 'Cancelled';
            $data['is_cancelled']   = 1;
           
            $match = Match::firstOrNew([
                'match_id' => $request->match_id
            ]);

            if($match->is_cancelled==0 && $match->status==1){

                $match->status= 4;
                $match->status_str= 'Cancelled';
                $match->is_cancelled= 1;
                $match->save();
            }else{
                $match->status= 4;
                $match->status_str= 'Cancelled';
                $match->is_cancelled= 1;
                $match->save();

                if($match->status==4){
                    return Redirect::to(route('match','search='.$match_id))->with('flash_alert_notice', 'This Match already Cancelled'); 
                }
                if($match->status!=1){
                    return Redirect::to(route('match','search='.$match_id))->with('flash_alert_notice', 'This Match can not be cancelled'); 
                }
            }
        }

        return Redirect::to(route('match','search='.$match_id))->with('flash_alert_notice', 'Match Cancelled successfully'); 


        $JoinContest = JoinContest::whereHas('user')->with('contest')
                        ->where('match_id',$request->match_id)
                        ->get()
                        ->transform(function($item,$key){
                            if(isset($item->contest) && $item->contest->entry_fees){
                                
                            $transaction_id = $item->match_id.$item->contest_id.$item->created_team_id.'-'.$item->user_id;

                            $wt =    WalletTransaction::firstOrNew(
                                    [
                                       'user_id' => $item->user_id,
                                       'transaction_id' => $transaction_id
                                    ]
                                );
                            $wt->user_id            = $item->user_id;   
                            $wt->amount             = $item->contest->entry_fees;  
                            $wt->payment_type       = 7;  
                            $wt->payment_type_string = "Refunded";
                            $wt->transaction_id     = $transaction_id;
                            $wt->payment_mode       = env('company_name');    
                            $wt->payment_status     = "success";
                            $wt->debit_credit_status = "+";   
                            $wt->save();


                            $wallet = Wallet::firstOrNew(
                                    [
                                       'user_id' => $item->user_id,
                                       'payment_type' => 4
                                    ]
                                );

                            $wallet->user_id        =  $item->user_id;
                            $wallet->amount = $wallet->amount+$item->contest->entry_fees;
                            $wallet->deposit_amount = $wallet->amount+$item->contest->entry_fees;
                            $wallet->save();

                            }
                        });               
        
        return Redirect::to(route('match','search='.$match_id))->with('flash_alert_notice', 'Match Cancelled successfully'); 

    }
  /**
    * @var $pd = prize distribution
    */
    public function triggerEmail(Request $request){

        $match_id = $request->match_id; 

        $pd = \DB::table('prize_distributions')
                ->where('match_id',$match_id)
                ->where('email_trigger',0)  
                ->get()  
                ->transform(function($item,$key)use($match_id){
                    $match = Match::where('match_id',$match_id)
                            ->select('match_id','title','short_title','status_note','format_str')->first();
                    $pd_user = \DB::table('prize_distributions')
                        ->where('match_id',$match_id)
                        ->where('user_id',$item->user_id);

                   // $item->prize_amount = $pd_user->sum('prize_amount');    
                   // $item->total_team = $pd_user->sum('team_name');

                    $email_content = [ //
                        'receipent_email'=> $item->email,
                        'subject'=> env('company_name').' | Prize',
                        'greeting'=> env('company_name'),
                        'first_name'=> ucfirst($item->name),
                        'content' => 'You have won the prize of Rs.<b>'.$item->prize_amount.'</b> for the <b>'.$match->title.'</b> match.',
                        'rank' => $item->rank
                        ];
                if($item->prize_amount>0){
                    $helper = new Helper;
                    $m = $helper->sendNotificationMail($email_content,'prize'); 
                }
                    
                \DB::table('prize_distributions')->where('id',$item->id)->update(['email_trigger'=>1]);  
                }); 
        return  Redirect::to(route('match','search='.$match_id.'&email=true'));
    }
    /*
     * Dashboard
     * */

    public function index(Match $match, Request $request) 
    {  
        $page_title = 'Match';
        $sub_page_title = 'View Match';
        $page_action = 'View Match';
        $match_start_date =null; 

        if ($request->ajax()) {
            $id = $request->get('id');
            $status = $request->get('status');
            $match = Match::find($id);
            $ss = $match->is_cancelled;
            $s = ($status == 1) ? ($status = 0) : ($status = 1);
            $match->is_cancelled = $s;
            $match->save();
            echo $ss;
            exit();
        }

        if($request->match_start_date){
            $match_start_date = $request->match_start_date;    
        }
               
        if($request->match_id && (($request->date_start && $request->date_end) || $request->status)){
            if($request->date_end && $request->date_start){
                $date_start = \Carbon\Carbon::createFromFormat('Y-m-d H:i',$request->date_start)
                ->setTimezone('UTC')
                ->format('Y-m-d H:i:s');

                $date_end = \Carbon\Carbon::createFromFormat('Y-m-d H:i', $request->date_end)
                    ->setTimezone('UTC')
                    ->format('Y-m-d H:i:s');

                $timestamp_start = strtotime($request->date_start);
                $timestamp_end   = strtotime($request->date_end);

                if($timestamp_start > $timestamp_end) {

                    return Redirect::to(route('match'))->with('End date should not be greater than start date');  
                }

            }
            
            $status = $request->status;
            if($status==1){
                $status_str = "Upcoming";
            }elseif($status==2){
                $status_str = "Completed";
            }elseif($status==3){
                $status_str = "Live";
            }elseif($status==4){
                $status_str = "Cancelled";
                $data['is_cancelled'] = 1;
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
                $data['status'] =   $request->status;
                $data['status_str'] =   $status_str;
            }
            
            \DB::table('matches')->where('match_id',$request->match_id)
                        ->update($data);

                     
            return Redirect::to('admin/match?search='.$request->match_id)->with('flash_alert_notice', 'Match updated successfully!');

        }

        // Search by name ,email and group
        $search = Input::get('search');
        $status = Input::get('status');
        if ((isset($search) || isset($status) || isset($match_start_date))) {
             
            $search = isset($search) ? Input::get('search') : '';
            $match = Match::with('teama','teamb')->where(function($query) use($search,$status,$match_start_date) {
                        if($match_start_date){
                            $query->where('date_start','LIKE',"%$match_start_date%");  
                        }
                        if (!empty($status)) { 
                            if($status==1){ 
                                $query->orderBy('timestamp_start','ASC');   
                              //  ->WhereMonth('date_start',date('m'));
                                $query->where('status',1);
                            }
                            if($status==2){ 
                                $query->orderBy('match_id','DESC');
                                $query->where('status',2);
                            }
                            if($status==3){ 
                                $query->orderBy('match_id','asc');
                                $query->where('status',3);
                            }
                            if($status==4){ 
                                $query->orderBy('timestamp_start','DESC');
                                $query->where('status',4);
                            }
                        }
                        if (!empty($search)) {
                            $query->orWhere('match_id',$search);
                            $query->orWhere('title', 'LIKE', "$search%");
                            $query->orWhere('short_title', 'LIKE', "$search%");
                        }    
                        
                    })
                   //  ->whereDate('date_start','<=',\Carbon\Carbon::today())
                    ->orderBy('date_start','desc')
                    ->Paginate($this->record_per_page);

                $match->transform(function($item,$key){
                    $playing11_teamA= \DB::table('team_a_squads')
                                ->where('playing11',"true")
                                ->where('match_id',$item->match_id)
                                ->get();
                    $playing11_teamB= \DB::table('team_b_squads')
                                    ->where('match_id',$item->match_id)
                                    ->where('playing11',"true")
                                    ->get();
                    if($playing11_teamA->count()){
                       $pid1 = $playing11_teamA->pluck('player_id')->toArray();
                    }
                    if($playing11_teamB->count()){
                       $pid2 = $playing11_teamB->pluck('player_id')->toArray();
                    }

                    if(isset($pid1) && isset($pid2)){
                        $item->playin11 = array_merge($pid1,$pid2);
                    }else{
                        $item->playin11 = [];
                    }

                    $item->playing11_teamA = $playing11_teamA;
                    $item->playing11_teamB = $playing11_teamB;
                                   
                $contests = CreateContest::where('match_id',$item->match_id)->get()
                            ->transform(function($item,$key){
                                $contest_name = \DB::table('contest_types')
                                        ->where('id',$item->contest_type)->first();
                                $item->contest_name = $contest_name->contest_type;
                                return $item;
                            });
                $item->contests = $contests;

                $players = \DB::table('players')
                        ->where('match_id',$item->match_id)
                        ->get()
                        ->groupBy('playing_role')
                        ->transform(function($item,$key){
                        //    $data[$key] = $item;

                            $item->transform(function($item,$key){ 
                                    $team_a = \DB::table('team_a')
                                        ->where('match_id',$item->match_id)
                                        ->where('team_id',$item->team_id)
                                        ->first();
                                    $team_b = \DB::table('team_b')
                                            ->where('match_id',$item->match_id)
                                            ->where('team_id',$item->team_id)
                                            ->first();
                                    if($team_a){
                                        $team_name = 
                                        ' <span class="btn-danger btn-xs">'.
                                        $team_a->short_name . '</span>';
                                    }

                                    if($team_b){
                                         $team_name = 
                                        ' <span class="btn-primary btn-xs">'.
                                        $team_b->short_name . '</span>';
                                    } 

                                    $item->team_name = $team_name??null;

                                    return $item;
                                });
                        
                            return $item;
                                
                            });
                $item->players = $players; 
                
                return $item;            

            });  
             
        } else { 
            $match = Match::with('teama','teamb')
                ->where('status','1')
               // ->WhereMonth('date_start',date('m'))
                ->whereDate('date_start','>=',\Carbon\Carbon::yesterday())
                ->orderBy('date_start','ASC')
                ->Paginate($this->record_per_page);
            $match->transform(function($item,$key){

                $playing11_teamA= \DB::table('team_a_squads')
                            ->where('playing11',"true")
                            ->where('match_id',$item->match_id)
                            ->get();
                $playing11_teamB= \DB::table('team_b_squads')
                                ->where('match_id',$item->match_id)
                                ->where('playing11',"true")
                                ->get();

                 if($playing11_teamA->count()){
                       $pid1 = $playing11_teamA->pluck('player_id')->toArray();
                    }
                    if($playing11_teamB->count()){
                       $pid2 = $playing11_teamB->pluck('player_id')->toArray();
                    }

                    if(isset($pid1) && isset($pid2)){
                        $item->playin11 = array_merge($pid1,$pid2);
                    }else{
                        $item->playin11 = [];
                    }


                $item->playing11_teamA = $playing11_teamA;
                $item->playing11_teamB = $playing11_teamB;
                $contests = CreateContest::where('match_id',$item->match_id)->get()
                            ->transform(function($item,$key){
                                $contest_name = \DB::table('contest_types')
                                        ->where('id',$item->contest_type)->first();
                                $item->contest_name = $contest_name->contest_type;
                                return $item;
                            });
                $item->contests = $contests;

                $players = \DB::table('players')
                        ->where('match_id',$item->match_id)
                        ->get()
                        ->groupBy('playing_role')
                        ->transform(function($item,$key){
                        //    $data[$key] = $item;

                            $item->transform(function($item,$key){ 
                                
                                    $team_a = \DB::table('team_a')
                                        ->where('match_id',$item->match_id)
                                        ->where('team_id',$item->team_id)
                                        ->first();
                                    $team_b = \DB::table('team_b')
                                            ->where('match_id',$item->match_id)
                                            ->where('team_id',$item->team_id)
                                            ->first();
                                    if($team_a){
                                        $team_name = 
                                        ' <span class="btn-danger btn-xs">'.
                                        $team_a->short_name . '</span>';
                                    }

                                    if($team_b){
                                         $team_name = 
                                        ' <span class="btn-primary btn-xs">'.
                                        $team_b->short_name . '</span>';
                                    } 

                                    $item->team_name = $team_name??null;

                                    return $item;
                                });
                        
                            return $item;
                                
                            });
                $item->players = $players;        
                return $item;            

            });
        }    
       // return ($match[0]->players['bowl']);
        return view('packages::match.index', compact('match','page_title', 'page_action','sub_page_title'));
    }

    public function create(Match $match)
    {
        $page_title     = 'Match';
        $page_action    = 'Create Match';
        $table_cname = \Schema::getColumnListing('matches');
        $except = ['id','created_at','updated_at','pid','team_id'];
       
        foreach ($table_cname as $key => $value) {

           if(in_array($value, $except )){
                continue;
           }
            $tables[] = $value;
        }

        return view('packages::match.create', compact('match', 'page_title', 'page_action','tables'));
    }

    /*
     * Save Group method
     * */

    public function store(Request $request, Match $program) 
    {   
        $program->fill(Input::all()); 
        $program->save();   
         
        return Redirect::to(route('match'))
                            ->with('flash_alert_notice', 'New Match  successfully created!');
    }


    /*
     * Edit Group method
     * @param 
     * object : $category
     * */

   public function edit($id) {
        $match = Match::find($id);
        $page_title = 'Match';
        $page_action = 'Match';

        $table_cname = \Schema::getColumnListing('matches');
        $except = ['id','created_at','updated_at','pid','team_id'];
        $data = [];
        foreach ($table_cname as $key => $value) {

           if(in_array($value, $except )){
                continue;
           }
             $tables[] = $value;
        }
        return view('packages::match.edit', compact( 'match', 'page_title','page_action', 'tables'));
    }

     public function update(Request $request, $id) {

        $match = Match::find($id);
        $data = [];
        $table_cname = \Schema::getColumnListing('matches');
        $except = ['id','created_at','updated_at','_token','_method','match_id','pid'];
        $data = [];
        foreach ($table_cname as $key => $value) {

           if(in_array($value, $except )){
                continue;
           }
            if($request->$value){
                $match->$value = $request->$value;
           }

        }

        $match->save();

        return Redirect::to(route('match'))
                        ->with('flash_alert_notice', ' Match  successfully updated.');
    }
    /*
     *Delete User
     * @param ID
     * 
     */
    public function destroy($program) { 
        
        Program::where('id',$program)->delete();
        return Redirect::to(route('program'))
                        ->with('flash_alert_notice', 'program  successfully deleted.');
    }

    public function show($id) {
        $matches = Match::find($id);
        $page_title     = 'Match';
        $page_action    = 'Show Match'; 
        $result = $matches;
        $match = Match::where('id',$matches->id)
            ->select('match_id','title','short_title','status_str','status_note','date_start','timestamp_start')->first()->toArray(); 

        $conetst = \DB::table('create_contests')->where('match_id',$matches->match_id)->get();    
         

        $team_a =  \DB::table('team_a_squads')->where('match_id',$matches->match_id)->pluck('player_id')->toArray();
        $team_b =  \DB::table('team_b_squads')->where('match_id',$matches->match_id)->pluck('player_id')->toArray(); 

        $team = array_merge($team_a,$team_b);
        

        $player =  \DB::table('players')
                    ->whereIn('pid',$team) 
                    ->where('match_id',$matches->match_id)
                    ->orderBy('title','ASC')
                    ->get(); 
        
        return view('packages::match.show', compact('player','conetst', 'result','match','page_title', 'page_action'));

    }

}