<?php
namespace Modules\Admin\Http\Controllers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests;
use Illuminate\Http\Request; 
use Modules\Admin\Models\User;
use Input, Validator, Auth, Paginate, Grids, HTML;
use Form, Hash, View, URL, Lang, Session, DB;
use Route, Crypt, Str;
use App\Http\Controllers\Controller;
use Illuminate\Http\Dispatcher;
use App\Helpers\Helper;
use Modules\Admin\Models\Roles;
use Modules\Admin\Models\Menu;
use Modules\Admin\Models\Player;
use App\Models\Matches as Match;

/**
 * Class PlayerController
 */
class PlayerController extends Controller {
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
        View::share('viewPage', 'players');
        View::share('sub_page_title', 'players');
        View::share('helper',new Helper);
        View::share('heading','players');
        View::share('route_url',route('players'));

        $this->record_per_page = Config::get('app.record_per_page');
    } 
    /*
     * Dashboard
     * */

    public function index(Player $players, Request $request)
    {
        $page_title = 'players';
        $sub_page_title = 'players';
        $page_action = 'View players';


        if ($request->ajax()) {
            $id = $request->get('id');
            $players = Player::find($id);
            $players->status = $s;
            $players->save();
            echo $s;
            exit();
        }

        // Search by name ,email and group
        $search = Input::get('search');
        $status = Input::get('status');
        if ((isset($search) && !empty($search))) {

            $search = isset($search) ? Input::get('search') : '';

            $players = Player::where(function($query) use($search,$status) {
                        if (!empty($search)) {
                             $query->Where('match_id', 'LIKE', $search);
                        }

                    })->Paginate($this->record_per_page);
        } else {
            $players = Player::Paginate($this->record_per_page);
            $players->transform(function($item,$key){
                $match = Match::where('match_id',$item->match_id)->first();
                $item->match = $match->title??null;
                return $item;
            });
        }
        $table_cname = \Schema::getColumnListing('players');
        $except = ['id','created_at','updated_at','batting_style','bowling_style','nationality','first_name','last_name','birthplace','thumb_url','logo_url','fielding_position','primary_team','middle_name','cid','recent_appearance','recent_match'];
        $data = [];
        foreach ($table_cname as $key => $value) {

           if(in_array($value, $except )){
                continue;
           }
             $tables[] = $value;
        }

        return view('packages::players.index', compact('players', 'page_title', 'page_action','sub_page_title','tables'));
    }

    /*
     * create Group method
     * */

    public function create(Player $players)
    {

        $page_title     = 'players';
        $page_action    = 'Create players';
        $table_cname = \Schema::getColumnListing('players');
        $except = ['id','created_at','updated_at'];
        $data = [];
        foreach ($table_cname as $key => $value) {

           if(in_array($value, $except )){
                continue;
           }
             $tables[] = $value;
        }

        return view('packages::players.create', compact('players', 'page_title', 'page_action','tables'));
    }

    /*
     * Save Group method
     * */

    public function store(Request $request, Player $players)
    {
        $data = [];
        $table_cname = \Schema::getColumnListing('players');
        $except = ['id','created_at','updated_at','_token','_method'];
        $data = [];
        foreach ($table_cname as $key => $value) {

           if(in_array($value, $except )){
                continue;
           }
            if($request->$value!=null){
                $players->$value = $request->$value;
           }
        }
        $players->save();
        return Redirect::to(route('players'))
                            ->with('flash_alert_notice', 'Player successfully created !');
        }

    /*
     * Edit Group method
     * @param
     * object : $menu
     * */

    public function edit($id) {
        $players = Player::find($id);
        $page_title = 'players';
        $page_action = 'players';

        $table_cname = \Schema::getColumnListing('players');
        $except = ['id','created_at','updated_at'];
        $data = [];
        foreach ($table_cname as $key => $value) {

           if(in_array($value, $except )){
                continue;
           }
             $tables[] = $value;
        }


        return view('packages::players.edit', compact( 'players', 'page_title','page_action', 'tables'));
    }

    public function update(Request $request, $id) {

        $players = Player::find($id);
        $data = [];
        $table_cname = \Schema::getColumnListing('players');
        $except = ['id','created_at','updated_at','_token','_method','match_id','pid'];
        $data = [];
        foreach ($table_cname as $key => $value) {

           if(in_array($value, $except )){
                continue;
           }
            if($request->$value){
                $players->$value = $request->$value;
           }

        }

        $players->save();

        return Redirect::to(route('players'))
                        ->with('flash_alert_notice', ' Player  successfully updated.');
    }
    /*
     * Delete User
     * @param ID
     *
     */
    public function destroy($id) {
        Player::where('id',$id)->delete();
        return Redirect::to(route('players'))
                        ->with('flash_alert_notice', ' players  successfully deleted.');

    }

}
