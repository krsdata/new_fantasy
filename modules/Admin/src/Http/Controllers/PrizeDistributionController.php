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
use Modules\Admin\Models\EditorPortfolio;
use Modules\Admin\Models\PrizeDistribution;

/**
 * Class MenuController
 */
class PrizeDistributionController extends Controller {
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
        View::share('viewPage', 'prizeDistribution');
        View::share('sub_page_title', 'prizeDistribution');
        View::share('helper',new Helper);
        View::share('heading','prize Distribution');
        View::share('route_url',route('prizeDistribution'));

        $this->record_per_page = Config::get('app.record_per_page');
    }


    /*
     * Dashboard
     * */

    public function index(PrizeDistribution $prizeDistribution, Request $request)
    {
        $page_title = 'prize Distribution';
        $sub_page_title = 'prize Distribution';
        $page_action = 'View prize Distribution';


        if ($request->ajax()) {
            $id = $request->get('id');
            $prizeDistribution = PrizeDistribution::find($id);
            $prizeDistribution->status = $s;
            $prizeDistribution->save();
            echo $s;
            exit();
        }

        // Search by name ,email and group
        $search = Input::get('search');
        $status = Input::get('status');
        if ((isset($search) && !empty($search))) {

            $search = isset($search) ? Input::get('search') : '';

            $prizeDistribution = PrizeDistribution::where(function($query) use($search,$status) {
                        if (!empty($search)) {
                             $query->Where('match_id', 'LIKE', $search);
                             $query->orWhere('email', 'LIKE', "%$search%");
                             $query->orWhere('name', 'LIKE', "%$search%");
                             $query->orWhere('mobile', 'LIKE', $search);
                        }

                    })
                    ->orderBy('id','desc')
                    ->where('prize_amount','>',0)
                    ->where('rank','>',0)
                    ->Paginate($this->record_per_page);
                 $prizeDistribution->transform(function($item,$key){
                
                
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
            $prizeDistribution = PrizeDistribution::
                                        orderBy('id','desc')
                                        ->where('rank','>',0)
                                        ->where('prize_amount','>',0)
                                        ->Paginate($this->record_per_page);
            $prizeDistribution->transform(function($item,$key){
                
                
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
        $table_cname = \Schema::getColumnListing('prize_distributions');
        $except = ['user_teams','id','updated_at','device_id','contest_type_id','default_contest_id','user_id','contest_id','created_team_id','match_team_id','email_trigger','user_name','email','first_prize'];
        $data = [];
        foreach ($table_cname as $key => $value) {

           if(in_array($value, $except )){
                continue;
           }
             $tables[] = $value;
        }

        return view('packages::prizeDistribution.index', compact('prizeDistribution', 'page_title', 'page_action','sub_page_title','tables'));
    }

    /*
     * create Group method
     * */

    public function create(PrizeDistribution $prizeDistribution)
    {

        $page_title     = 'prize Distribution';
        $page_action    = 'Create prize Distribution';
        $table_cname = \Schema::getColumnListing('prize_distributions');
        $except = ['id','created_at','updated_at'];
        $data = [];
        foreach ($table_cname as $key => $value) {

           if(in_array($value, $except )){
                continue;
           }
             $tables[] = $value;
        }

        return view('packages::prizeDistribution.create', compact('prizeDistribution', 'page_title', 'page_action','tables'));
    }

    /*
     * Save Group method
     * */

    public function store(Request $request, PrizeDistribution $prizeDistribution)
    {
        $data = [];
        $table_cname = \Schema::getColumnListing('prize_distributions');
        $except = ['id','created_at','updated_at','_token','_method'];
        $data = [];
        foreach ($table_cname as $key => $value) {

           if(in_array($value, $except )){
                continue;
           }
            if($request->$value!=null){
                $prizeDistribution->$value = $request->$value;
           }
        }
        $prizeDistribution->save();
        return Redirect::to(route('prizeDistribution'))
                            ->with('flash_alert_notice', 'Player points successfully created !');
        }

    /*
     * Edit Group method
     * @param
     * object : $menu
     * */

    public function edit($id) {
        $prizeDistribution = PrizeDistribution::find($id);
        $page_title = 'prize Distribution';
        $page_action = 'prize Distribution';

        $table_cname = \Schema::getColumnListing('prize_distributions');
        $except = ['id','created_at','updated_at'];
        $data = [];
        foreach ($table_cname as $key => $value) {

           if(in_array($value, $except )){
                continue;
           }
             $tables[] = $value;
        }


        return view('packages::prizeDistribution.edit', compact( 'prizeDistribution', 'page_title','page_action', 'tables'));
    }

    public function update(Request $request, $id) {

        $prizeDistribution = PrizeDistribution::find($id);
        $data = [];
        $table_cname = \Schema::getColumnListing('prize_distributions');
        $except = ['id','created_at','updated_at','_token','_method','match_id','pid'];
        $data = [];
        foreach ($table_cname as $key => $value) {

           if(in_array($value, $except )){
                continue;
           }
            if($request->$value){
                $prizeDistribution->$value = $request->$value;
           }
        }
        $prizeDistribution->save();

        return Redirect::to(route('prizeDistribution'))
                        ->with('flash_alert_notice', ' Points  successfully updated.');
    }
    /*
     * Delete User
     * @param ID
     *
     */
    public function destroy($id) {
        PrizeDistribution::where('id',$id)->delete();
        return Redirect::to(route('prizeDistribution'))
                        ->with('flash_alert_notice', ' prizeDistribution  successfully deleted.');

    }

}
