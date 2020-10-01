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
use Modules\Admin\Models\JoinContest;

/**
 * Class MenuController
 */
class LeaderBoardController extends Controller {
    /**
     * @var  Repository
     */
    /**
     * Displays all admin.
     *
     * @return \Illuminate\View\View
     */
    public function __construct() {
        $this->middleware('admin');
        View::share('viewPage', 'leaderBoard');
        View::share('sub_page_title', 'leaderBoard');
        View::share('helper',new Helper);
        View::share('heading','leaderBoard');
        View::share('route_url',route('leaderBoard'));

        $this->record_per_page = Config::get('app.record_per_page');
    }


    /*
     * Dashboard
     * */

    public function index(LeaderBoard $leaderBoard, Request $request)
    {
        $page_title = 'prize leaderBoard';
        $sub_page_title = 'prize leaderBoard';
        $page_action = 'View  leaderBoard';


        if ($request->ajax()) {
            $id = $request->get('id');
            $leaderBoard = LeaderBoard::find($id);
            $leaderBoard->status = $s;
            $leaderBoard->save();
            echo $s;
            exit();
        }

        // Search by name ,email and group
        $search = Input::get('search');
        $status = Input::get('status');
        if ((isset($search) && !empty($search))) {

            $search = isset($search) ? Input::get('search') : '';

            $leaderBoard = LeaderBoard::where(function($query) use($search,$status) {
                        if (!empty($search)) {
                             $query->Where('match_id', 'LIKE', $search);
                             $query->orWhere('email', 'LIKE', "%$search%");
                             $query->orWhere('name', 'LIKE', "%$search%");
                             $query->orWhere('mobile', 'LIKE', $search);
                        }

                    })->Paginate($this->record_per_page);
                 $leaderBoard->transform(function($item,$key){
                
                
                $palyer_id[]  = $item->captain;
                $palyer_id[]  = $item->vice_captain;
                $palyer_id[]  = $item->trump;
                 
                $user_teams  = array_merge($palyer_id, json_decode($item->user_teams,true));


                $palyer = \DB::table('match_player_points')
                                        ->where('match_id',$item->match_id)
                                        ->whereIn('pid',$user_teams) 
                                        ->get();
                
                $item->captain = $palyer->where('pid',$item->captain)->first()->name;
               
                $item->trump = ($palyer->where('pid',$item->trump)->first())->name;
                $item->vice_captain = ($palyer->where('pid',$item->vice_captain))->first()->name;
                

                $palyer_name = "";
                foreach ($palyer  as $key => $value) {
                     $palyer_name = $palyer_name.'<li>'.$value->name.'</li>';
                }
                $item->user_teams = $palyer_name;
                
                $match= \DB::table('matches')->where('match_id',$item->match_id)->first(); 
                $item->match_id = $match->title;
                 
                return $item; 
            });
        } else {
            $leaderBoard = LeaderBoard::orderBy('rank','asc')->Paginate($this->record_per_page);
            $leaderBoard->transform(function($item,$key){
                
                
                $palyer_id[]  = $item->captain;
                $palyer_id[]  = $item->vice_captain;
                $palyer_id[]  = $item->trump;
                 
                $user_teams  = array_merge($palyer_id, json_decode($item->user_teams,true));


                $palyer = \DB::table('match_player_points')
                                        ->where('match_id',$item->match_id)
                                        ->whereIn('pid',$user_teams) 
                                        ->get();
                
                $item->captain = $palyer->where('pid',$item->captain)->first()->name;
               
                $item->trump = ($palyer->where('pid',$item->trump)->first())->name;
                $item->vice_captain = ($palyer->where('pid',$item->vice_captain))->first()->name;
                

                $palyer_name = "";
                foreach ($palyer  as $key => $value) {
                     $palyer_name = $palyer_name.'<li>'.$value->name.'</li>';
                }
                $item->user_teams = $palyer_name;
                
                $match= \DB::table('matches')->where('match_id',$item->match_id)->first(); 
                $item->match_id = $match->title;
                 
                return $item; 
            });
        } 
        $table_cname = \Schema::getColumnListing('join_contests');
        $except = ['user_teams','id','created_at','updated_at','device_id','contest_type_id','default_contest_id','user_id','contest_id','created_team_id','match_team_id'];
        $data = [];
        foreach ($table_cname as $key => $value) {

           if(in_array($value, $except )){
                continue;
           }
             $tables[] = $value;
        }

        return view('packages::leaderBoard.index', compact('leaderBoard', 'page_title', 'page_action','sub_page_title','tables'));
    }

    /*
     * create Group method
     * */

    public function create(LeaderBoard $leaderBoard)
    {

        $page_title     = 'prize leaderBoard';
        $page_action    = 'Create prize leaderBoard';
        $table_cname = \Schema::getColumnListing('join_contests');
        $except = ['id','created_at','updated_at'];
        $data = [];
        foreach ($table_cname as $key => $value) {

           if(in_array($value, $except )){
                continue;
           }
             $tables[] = $value;
        }

        return view('packages::leaderBoard.create', compact('leaderBoard', 'page_title', 'page_action','tables'));
    }

    /*
     * Save Group method
     * */

    public function store(Request $request, LeaderBoard $leaderBoard)
    {
        $data = [];
        $table_cname = \Schema::getColumnListing('join_contests');
        $except = ['id','created_at','updated_at','_token','_method'];
        $data = [];
        foreach ($table_cname as $key => $value) {

           if(in_array($value, $except )){
                continue;
           }
            if($request->$value!=null){
                $leaderBoard->$value = $request->$value;
           }
        }
        $leaderBoard->save();
        return Redirect::to(route('leaderBoard'))
                            ->with('flash_alert_notice', 'Player points successfully created !');
        }

    /*
     * Edit Group method
     * @param
     * object : $menu
     * */

    public function edit($id) {
        $leaderBoard = LeaderBoard::find($id);
        $page_title = 'leaderBoard';
        $page_action = 'leaderBoard';

        $table_cname = \Schema::getColumnListing('join_contests');
        $except = ['id','created_at','updated_at'];
        $data = [];
        foreach ($table_cname as $key => $value) {

           if(in_array($value, $except )){
                continue;
           }
             $tables[] = $value;
        }


        return view('packages::leaderBoard.edit', compact( 'leaderBoard', 'page_title','page_action', 'tables'));
    }

    public function update(Request $request, $id) {

        $leaderBoard = LeaderBoard::find($id);
        $data = [];
        $table_cname = \Schema::getColumnListing('join_contests');
        $except = ['id','created_at','updated_at','_token','_method','match_id','pid'];
        $data = [];
        foreach ($table_cname as $key => $value) {

           if(in_array($value, $except )){
                continue;
           }
            if($request->$value){
                $leaderBoard->$value = $request->$value;
           }
        }
        $leaderBoard->save();

        return Redirect::to(route('leaderBoard'))
                        ->with('flash_alert_notice', ' Points  successfully updated.');
    }
    /*
     * Delete User
     * @param ID
     *
     */
    public function destroy($id) {
        LeaderBoard::where('id',$id)->delete();
        return Redirect::to(route('leaderBoard'))
                        ->with('flash_alert_notice', ' leaderBoard  successfully deleted.');

    }

}
