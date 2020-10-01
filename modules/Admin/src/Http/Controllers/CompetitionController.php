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
use Modules\Admin\Models\Competition;
use App\Models\Matches;

/**
 * Class MenuController
 */
class CompetitionController extends Controller {
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
        View::share('viewPage', 'Competition');
        View::share('sub_page_title', 'Competition');
        View::share('helper',new Helper);
        View::share('heading','Competition');
        View::share('route_url',route('competition'));

        $this->record_per_page = Config::get('app.record_per_page');
    }


    /*
     * Dashboard
     * */

    public function index(Competition $competition, Request $request)
    {
        $page_title = 'Competition';
        $sub_page_title = 'Competition';
        $page_action = 'View Competition'; 
        if ($request->ajax()) {
            $id = $request->get('id');
            $Competition = Competition::find($id);
            $Competition->status = $s;
            $Competition->save();
            echo $s;
            exit();
        }

        // Search by name ,email and group
        $search = Input::get('search');
        $status = Input::get('status');
        $match_id = Matches::where('title','LIKE',"%$search%")
                            ->orWhere('short_title','LIKE',"%$search%")
                            ->orWhere('match_id','LIKE',"%$search%")
                            ->get('match_id')->pluck('match_id');

        if ((isset($search) && !empty($search))) { 

            $competition = Competition::where(function($query) use($search,$status,$match_id) {
                        if (!empty($search)) {
                             $query->whereIn('match_id', $match_id);
                             $query->orWhere('title', $search);
                         } 
                    })->Paginate($this->record_per_page);
             $competition->transform(function($item,$key){
                  
                    $Matches = Matches::where('match_id',$item->match_id)->first();
                    if($Matches){
                        $item->match_id     = $Matches->match_id;
                        $item->short_title  = $Matches->short_title;
                        $item->date_start   = $Matches->date_start;
                        $item->date_end     = $Matches->date_end;
                        $item->timestamp_start = $Matches->timestamp_start;
                        $item->timestamp_end = $Matches->timestamp_end;
                    } 
                   
                    return $item; 
            });
        } else {
            $competition = Competition::whereHas('match')
                    ->whereMonth('datestart', '>=', date('m'))
                    ->orderBy('datestart','asc')
                    ->Paginate($this->record_per_page);
                                                    

            $competition->transform(function($item,$key){
                  
                    $Matches = Matches::where('match_id',$item->match_id)->first();
                    if($Matches){
                        $item->match_id     = $Matches->match_id;
                        $item->short_title  = $Matches->short_title;
                        $item->date_start   = $Matches->date_start;
                        $item->date_end     = $Matches->date_end;
                        $item->timestamp_start = $Matches->timestamp_start;
                        $item->timestamp_end = $Matches->timestamp_end;
                    } 
                   
                    return $item; 
            });

        } 

        $table_cname = \Schema::getColumnListing('competitions');
        
        $except = ['id','created_at','updated_at','abbr','type','status','total_rounds','total_teams','country'];
        $data = [];
      //  $tables[] = 'name';
      //  $tables[] = 'email';
        foreach ($table_cname as $key => $value) {

           if(in_array($value, $except )){
                continue;
           }
            $tables[] = $value;
        }
        return view('packages::competition.index', compact('competition', 'page_title', 'page_action','sub_page_title','tables'));
    }

    /*
     * create Group method
     * */

    public function create(Competition $competition)
    {

        $page_title     = 'competition';
        $page_action    = 'Create competition';
        $table_cname = \Schema::getColumnListing('competitions');
        $except = ['id','created_at','updated_at','validate_user','bonus_amount','referal_amount','prize_amount','deposit_amount','usable_amount','usable_amount_validation','total_withdrawal_amount','prize_distributed_id'];
        $data = [];
        foreach ($table_cname as $key => $value) {

           if(in_array($value, $except )){
                continue;
           }
             $tables[] = $value;
        }

        return view('packages::competition.create', compact('competition', 'page_title', 'page_action','tables'));
    }

    /*
     * Save Group method
     * */

    public function store(Request $request, Competition $competition)
    {
        $data = [];
        $table_cname = \Schema::getColumnListing('competitions');
        $except = ['id','created_at','updated_at','_token','_method'];
        $data = [];
        foreach ($table_cname as $key => $value) {

           if(in_array($value, $except )){
                continue;
           }
            if($request->$value!=null){
                $competition->$value = $request->$value;
           }
        }
        
        $competition->save();
        return Redirect::to(route('competition'))
                            ->with('flash_alert_notice', 'Competition successfully created !');
        }

    /*
     * Edit Group method
     * @param
     * object : $menu
     * */

    public function edit($id) {
        $competition = Competition::find($id);
        $page_title = 'competition';
        $page_action = 'competition';

        $table_cname = \Schema::getColumnListing('competitions');
        $except = ['id','created_at','updated_at','bonus_amount','referal_amount','prize_amount','deposit_amount','usable_amount','usable_amount_validation','total_withdrawal_amount','prize_distributed_id'];
        $data = [];
        foreach ($table_cname as $key => $value) {

           if(in_array($value, $except )){
                continue;
           }
             $tables[] = $value;
        }

        return view('packages::competition.edit', compact( 'competition', 'page_title','page_action', 'tables'));
    }

    public function update(Request $request, $id) {

        $competition = Competition::find($id);
        $data = [];
        $table_cname = \Schema::getColumnListing('competitions');
        $except = ['id','created_at','updated_at','_token','_method'];
        $data = [];
        foreach ($table_cname as $key => $value) {

           if(in_array($value, $except )){
                continue;
           }
            if($request->$value){
                $competition->$value = $request->$value;
           }
        }

        $competition->save();

        return Redirect::to(route('competition'))
                        ->with('flash_alert_notice', ' Competition  successfully updated.');
    }
    /*
     * Delete User
     * @param ID
     *
     */
    public function destroy($id) {
        #PrizeDistribution::where('id',$id)->delete();
        return Redirect::to(route('competition'))
                        ->with('flash_alert_notice', ' competition  successfully deleted.');

    }

}
