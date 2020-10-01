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
use App\Models\Matches as Match;
use Modules\Admin\Models\UpdatePlayerPoints;

/**
 * Class MenuController
 */
class UpdatePlayerPointsController extends Controller {
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
        View::share('viewPage', 'updatePlayerPoints');
        View::share('sub_page_title', 'updatePlayerPoints');
        View::share('helper',new Helper);
        View::share('heading','updatePlayerPoints');
        View::share('route_url',route('updatePlayerPoints'));

        $this->record_per_page = Config::get('app.record_per_page');
    }

    public function updatePoint(Request $request){
        $data = [];
        $pp = UpdatePlayerPoints::find($request->id);
        foreach ($request->except('action','id') as $key => $value) {
           $pp->$key = $value; 
           $data[] = $key;
        }
        $pp->save();
        return $data;
    }

    /*
     * Dashboard
     * */

    public function index(UpdatePlayerPoints $updatePlayerPoints, Request $request)
    {
        $page_title = 'updatePlayerPoints';
        $sub_page_title = 'updatePlayerPoints';
        $page_action = 'View updatePlayerPoints';


        if ($request->ajax()) {
            $id = $request->get('id');
            $updatePlayerPoints = UpdatePlayerPoints::find($id);
            $updatePlayerPoints->status = $s;
            $updatePlayerPoints->save();
            echo $s;
            exit();
        }

        // Search by name ,email and group
        $search = Input::get('search');
        $status = Input::get('status');
        if ((isset($search) && !empty($search))) {

            $search = isset($search) ? Input::get('search') : '';

            $updatePlayerPoints = UpdatePlayerPoints::where(function($query) use($search,$status) {
                        if (!empty($search)) {
                             $query->Where('match_id', $search);
                        }
                        if (!empty($search)) {
                             $query->orWhere('pid', $search);
                        }

                    })->Paginate($this->record_per_page);
            $updatePlayerPoints->transform(function($item,$key){
                $match =  Match::where('match_id',$item->match_id)->first();
                if($match){
                    $item->match_title = $match->short_title??null;    
                }
                
                return $item; 
            });
        } else {
            $updatePlayerPoints = UpdatePlayerPoints::Paginate($this->record_per_page);
            $updatePlayerPoints->transform(function($item,$key){
                $match =  Match::where('match_id',$item->match_id)->first();

                if($match){
                    $item->match_title = $match->short_title??null;    
                }
                return $item; 
            });
        }
        
        $table_cname = \Schema::getColumnListing('match_player_points');
        $except = ['id','created_at','updated_at','er','thirty','bonus'];
        $data = [];
        $tables[]  = 'match_title';
       // $editable[1] = [1,$value];    
        //$editable[] = [0,'id'];
         $i=2; 
        foreach ($table_cname as $key => $value) {

           if(in_array($value, $except )){
                continue;
           } 
             
             $tables[]   = $value;  
             $editable[] = [$i,$value];
              $i++;
              
        }   
        $editable = json_encode($editable);
       // dd( $editable);
        return view('packages::updatePlayerPoints.index', compact('updatePlayerPoints', 'page_title', 'page_action','sub_page_title','tables','editable'));
    }

    /*
     * create Group method
     * */

    public function create(UpdatePlayerPoints $updatePlayerPoints)
    {

        $page_title     = 'updatePlayerPoints';
        $page_action    = 'Create updatePlayerPoints';
        $table_cname = \Schema::getColumnListing('match_player_points');
        $except = ['id','created_at','updated_at'];
        $data = [];
        foreach ($table_cname as $key => $value) {

           if(in_array($value, $except )){
                continue;
           }
             $tables[] = $value;
        }

        return view('packages::updatePlayerPoints.create', compact('updatePlayerPoints', 'page_title', 'page_action','tables'));
    }

    /*
     * Save Group method
     * */

    public function store(Request $request, UpdatePlayerPoints $updatePlayerPoints)
    {
        $data = [];
        $table_cname = \Schema::getColumnListing('match_player_points');
        $except = ['id','created_at','updated_at','_token','_method'];
        $data = [];
        foreach ($table_cname as $key => $value) {

           if(in_array($value, $except )){
                continue;
           }
            if($request->$value!=null){
                $updatePlayerPoints->$value = $request->$value;
           }
        }
        $updatePlayerPoints->save();
        return Redirect::to(route('updatePlayerPoints'))
                            ->with('flash_alert_notice', 'Player points successfully created !');
        }

    /*
     * Edit Group method
     * @param
     * object : $menu
     * */

    public function edit($id) {
        $updatePlayerPoints = UpdatePlayerPoints::find($id);
        $page_title = 'UpdatePlayerPoints';
        $page_action = 'UpdatePlayerPoints';

        $table_cname = \Schema::getColumnListing('match_player_points');
        $except = ['id','created_at','updated_at'];
        $data = [];
        foreach ($table_cname as $key => $value) {

           if(in_array($value, $except )){
                continue;
           }
             $tables[] = $value;
        }


        return view('packages::updatePlayerPoints.edit', compact( 'updatePlayerPoints', 'page_title','page_action', 'tables'));
    }

    public function update(Request $request, $id) {

        $updatePlayerPoints = UpdatePlayerPoints::find($id);
        $data = [];
        $table_cname = \Schema::getColumnListing('match_player_points');
        $except = ['id','created_at','updated_at','_token','_method','match_id','pid'];
        $data = [];
        foreach ($table_cname as $key => $value) {

           if(in_array($value, $except )){
                continue;
           }
            if($request->$value){
                $updatePlayerPoints->$value = $request->$value;
           }

        }

        $updatePlayerPoints->save();

        return Redirect::to(route('updatePlayerPoints'))
                        ->with('flash_alert_notice', ' Points  successfully updated.');
    }
    /*
     * Delete User
     * @param ID
     *
     */
    public function destroy($id) {
        UpdatePlayerPoints::where('id',$id)->delete();
        return Redirect::to(route('updatePlayerPoints'))
                        ->with('flash_alert_notice', ' UpdatePlayerPoints  successfully deleted.');

    }

}
