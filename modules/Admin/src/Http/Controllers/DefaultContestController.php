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
use Response; 
use Modules\Admin\Http\Requests\DefaultContestRequest;
use Modules\Admin\Models\DefaultContest;
use Modules\Admin\Models\ContestType;
use Modules\Admin\Models\PrizeBreakups;
use App\Models\Matches;

/**
 * Class AdminController
 */
class DefaultContestController extends Controller {
    /**
     * @var  Repository
     */

    /**
     * Displays all admin.
     *
     * @return \Illuminate\View\View
     */
    public function __construct(DefaultContest $defaultContest) { 
        $this->middleware('admin');
        View::share('viewPage', 'Default Contest');
        View::share('sub_page_title', 'Default Contest');
        View::share('helper',new Helper);
        View::share('heading','Default Contest');
        View::share('route_url',route('defaultContest')); 
        $this->record_per_page = Config::get('app.record_per_page'); 
    }
    /*
     * Dashboard
     * */

    public function index(DefaultContest $defaultContest, Request $request) 
    { 
        $page_title = 'Default Contest';
        $sub_page_title = 'View Default Contest';
        $page_action = 'View Default Contest'; 
 
        // Search by name ,email and group
        $search = Input::get('search');
        $status = Input::get('status');
        $contest_type = $request->get('contest_type');
        if ((isset($search) || !empty($contest_type))) {
            $defaultContest = DefaultContest::where(function($query) use($search,$status,$contest_type) {
                        if (!empty($contest_type)) {
                            $query->Where('contest_type', $contest_type);
                        }
                        if (!empty($search)) {
                            $query->orWhere('entry_fees',$search);
                        }
                        if (!empty($search)) {
                            $query->orWhere('total_spots',$search);
                        }
                        
                    })->Paginate(20);
        } else {
            $defaultContest = DefaultContest::orderBy('id','DESC')->Paginate(20);
        }
        
        $contest_type   = ContestType::pluck('contest_type','id')->toArray();
        
        return view('packages::defaultContest.index', compact('defaultContest','page_title', 'page_action','sub_page_title','contest_type'));
    }

    /*
     * create DefaultContest
     * */

    public function create(DefaultContest $defaultContest , Request $request) 
    {
        $page_title     = 'Default Contest';
        $page_action    = 'Create Default Contest'; 
        $contest_type   = ContestType::pluck('contest_type','id'); 
        
        $match = false;    
        if($request->match_id){
            $match_id = $request->match_id;

            $match = \App\Models\Matches::where('match_id',$match_id)->first();
            if($match){
                $match = $match->match_id;
            }
        }

        return view('packages::defaultContest.create', compact( 'defaultContest','page_title', 'page_action','contest_type','match'));
    }

    /*
     * Save Group method
     * */
    //DefaultContestRequest
    public function store(DefaultContestRequest $request, DefaultContest $defaultContest) 
    {   
        $defaultContest->fill(Input::all()); 
        $defaultContest->cancellation = $request->cancellation?true:false;
        $defaultContest->bonus_contest = $request->bonus_contest?true:false;
        $defaultContest->usable_bonus = $request->usable_bonus;
        $defaultContest->save(); 

        $default_contest_id = $defaultContest->id;

        if($request->match_id){
            $match  = Matches::where('match_id',$request->match_id)->get('match_id');
            \DB::table('matches')->where('match_id',$request->match_id)->update(['is_free'=>$request->is_free]);
            
        }else{
            $match  = Matches::where('status',1)->get('match_id');
        }
        $sort_by = \DB::table('contest_types')->where('id',$request->contest_type)->first()->sort_by??0;
        $request->merge(['sort_by'=>$sort_by]);
        $request->merge(['filled_spot' => 0]);
        foreach ($match as $key => $result) {

            $request->merge(['match_id' => $result->match_id]);
            $request->merge(['default_contest_id' => $default_contest_id]);

            \DB::table('create_contests')->insert($request->except('_token'));
        }
         
        return Redirect::to(route('defaultContest'))
                            ->with('flash_alert_notice', 'New Contest successfully created!');
    }

    /*
     * Edit Group method
     * @param 
     * object : $categoryprize
     * */

    public function edit(Request $request, $id) {
        $defaultContest = DefaultContest::find($id);
        //dd($defaultContest);
        $page_title     = 'Default Contest';
        $page_action    = 'Edit Default Contest'; 
        $contest_type   = ContestType::pluck('contest_type','id');

        $match = false;    
        if($defaultContest->match_id){
            $match = $defaultContest->match_id;
        }

        return view('packages::defaultContest.edit', compact('defaultContest', 'page_title', 'page_action','contest_type','match'));
    }

    public function update(Request $request, $id) {
        
        $action = null;
        if($request->prize_break){ 
             $action = "prize_break";
        } 
        if($request->rank_list){ 
            $action = "rank_list";
        } 
        //dd($request->all());
        switch ($action) {
            case 'prize_break': 
                  if($request->prize_break){

                    $from   = $request->rank_from;
                    $to     = $request->rank_upto;
                    $prize  = $request->prize_amount;
                    $prize_break_id = $request->prize_break_id;

                    $pb = PrizeBreakups::where('default_contest_id',$request->default_contest_id)->pluck('id')->toArray();
                    
                    if($prize_break_id && isset($pb) && count($pb)  > count($request->prize_break_id))
                    {
                      foreach ($pb as $key => $id) {
                        if(!in_array($id, $prize_break_id)){
                            PrizeBreakups::where('id',$id)
                                       ->delete();
                        }
                      }
                    }  
                    
                    foreach ($request->rank_from as $key => $value) {
                      if($prize[$key]==null){
                           continue;
                      } 
                      PrizeBreakups::updateOrCreate(
                            [
                               'default_contest_id'  => $request->default_contest_id,
                               'contest_type_id'   => $request->contest_type_id,
                               'id' => $prize_break_id[$key]??null
                            ],
                            [
                               'default_contest_id'  => $request->default_contest_id,
                               'contest_type_id'   => $request->contest_type_id,
                               'rank_from' =>  $from[$key],
                               'rank_upto' =>  $to[$key],
                               'prize_amount' =>  $prize[$key],
                               'match_id'  => $request->match_id
                            ]);

                    } 

                  }

                $url = Url::previous();
                  
                return Redirect::to($url)
                        ->with('flash_alert_notice', 'Prize Breakups successfully updated.');


                break;
             case 'rank_list':
                # code...
             
                return Redirect::to(route('defaultContest.show',$id).'?list='.$request->rank_list);
               
                break;
            
            default:
                # code...
                break;
        } 

        $defaultContest = DefaultContest::find($id);
        $defaultContest->fill(Input::all()); 
        
        $defaultContest->bonus_contest = $request->bonus_contest?true:false;
        $defaultContest->usable_bonus = $request->usable_bonus;
        $defaultContest->cancellation = $request->cancellation?true:false;
        $defaultContest->save(); 
        $default_contest_id = $id;

        $match = null;
        $match1  = Matches::where('status',1)
                    ->get('match_id');
        if($match1){
          $match  = $match1;
        }
        $match2  = Matches::where('match_id',$request->match_id)->get('match_id');
        if($match2->count()){
          $match  = $match2;
        }
        foreach ($match as $key => $result) {
            $request->merge(['match_id' => $result->match_id]);
            $request->merge(['default_contest_id' => $default_contest_id]);
            $request->merge(['prize_percentage'=>$request->prize_percentage]);

            $sort_by = \DB::table('contest_types')->where('id',$request->contest_type)->first()->sort_by??0;
            $request->merge(['sort_by'=>$sort_by]);

           $cont =  \DB::table('create_contests')
                    ->where('default_contest_id',$default_contest_id)
                    ->where('match_id',$result->match_id)->count();
            $request_data = $request->except(['_token','_method']);

            if($cont){
              \DB::table('create_contests')
                    ->where('default_contest_id',$default_contest_id)
                    ->where('match_id',$result->match_id)
                    ->update($request_data);
            } else{
                \DB::table('create_contests')->insert($request->except('_token','_method'));  
            }
        }

        return Redirect::to(route('defaultContest'))
                        ->with('flash_alert_notice', 'Default Contest  successfully updated.');
    }
    /*
     *Delete User
     * @param ID
     * 
     */
    public function destroy($id) { 

        DefaultContest::where('id',$id)->delete();
        $contest = \DB::table('create_contests')
                    ->where('default_contest_id',$id)
                    ->where('filled_spot',0)
                    ->update([
                        'deleted_at'=>date('Y-m-d h:i'),
                        'is_cancelled'=>1
                      ]);

        return Redirect::to(route('defaultContest'))
                        ->with('flash_alert_notice', 'Contest successfully deleted.');
    }

    public function show(Request $request, $id) {
	
        $page_title     = 'Contest Prize Breakup';
        $page_action    = 'Show   Contest Prize Breakup'; 
        $expected_amount    =   "";
        
        try{
            $defaultContest = DefaultContest::find((int)$id);
          if(!$defaultContest){
        		return Redirect::to(route('defaultContest'));
        	} 
          $contestType   =  DefaultContest::with('contestType')->where('id',$id)->first();
            
          $contest_type  = [$contestType->contestType->id=>$contestType->contestType->contest_type] ;

          $match = false;    
          if($request->get('match_id')){
                $match_id = $request->match_id;

                $match = \App\Models\Matches::where('match_id',$match_id)->first();
                if($match){
                    $match = $match->match_id;
                }
            }

          $prizeBreakup = \DB::table('prize_breakups')
                        ->where('default_contest_id',$defaultContest->id)
                        ->where('contest_type_id',$defaultContest->contest_type)
                        ->get();

            $rank_list = $request->list??$prizeBreakup->count();
            $expected_amount =  $contestType->entry_fees*$contestType->total_spots;


            $html       = view::make('packages::defaultContest.addPrizeForm',compact('expected_amount','rank_list','prizeBreakup'));
            
           $default_contest_id = $id;
            return view('packages::defaultContest.prizeBreakup', compact( 'defaultContest','page_title', 'page_action','contest_type','match','expected_amount','html','rank_list','default_contest_id'));
            
         } catch(Exception $e){
         	 
        } 

    }
}

