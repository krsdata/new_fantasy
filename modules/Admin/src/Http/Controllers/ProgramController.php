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
use Modules\Admin\Models\Contact; 
use Modules\Admin\Models\Category;
use Modules\Admin\Models\ContactGroup;
use Modules\Admin\Models\Program;
use Response; 
use Modules\Admin\Http\Requests\ProgramRequest;
/**
 * Class AdminController
 */
class ProgramController extends Controller {
    /**
     * @var  Repository
     */

    /**
     * Displays all admin.
     *
     * @return \Illuminate\View\View
     */
    public function __construct(Contact $contact) { 
        $this->middleware('admin');
        View::share('viewPage', 'Campaign');
        View::share('sub_page_title', 'Campaign');
        View::share('helper',new Helper);
        View::share('heading','Campaign');
        View::share('route_url',route('program')); 
        $this->record_per_page = Config::get('app.record_per_page'); 
    }
   
    /*
     * Dashboard
     * */

    public function index(Contact $contact, Request $request) 
    { 
        $page_title = 'Campaign';
        $sub_page_title = 'View Campaign';
        $page_action = 'View Campaign'; 


        if ($request->ajax()) {
            $id = $request->get('id'); 
            $category = Program::find($id); 
            $category->status = $s;
            $category->save();
            echo $s;
            exit();
        }

        // Search by name ,email and group
        $search = Input::get('search');
        $status = Input::get('status');
        if ((isset($search) && !empty($search))) {

            $search = isset($search) ? Input::get('search') : '';
               
            $programs = Program::where(function($query) use($search,$status) {
                        if (!empty($search)) {
                            $query->Where('campaign_name', 'LIKE', "%$search%");
                        }
                        
                    })->Paginate($this->record_per_page);
        } else {
            $programs = Program::Paginate($this->record_per_page);
        }
         
        
        return view('packages::program.index', compact('programs','page_title', 'page_action','sub_page_title'));
    }

    /*
     * create Group method
     * */

    public function create(Program $program) 
    {
        $page_title     = 'Campaign';
        $page_action    = 'Create Campaign';
        $status         = [ 0=>'Select Reward Type',
                            1=>'Fixed',
                            2=>'Percentage'
                        ];
                      
        return view('packages::program.create', compact( 'program','status','page_title', 'page_action'));
    }

    

    /*
     * Save Group method
     * */

    public function store(Request $request, Program $program) 
    {   
        $time = date('h:i:s A');

        $start_date = $request->start_date;
        $end_date   = $request->end_date;
        
        $timestamp_sd  = strtotime($start_date);
        $timestamp_ed  = strtotime($end_date);

        $request->merge(['start_date'   =>  date('Y-m-d', $timestamp_sd)]); 
        $request->merge(['end_date'     =>  date('Y-m-d', $timestamp_ed)]);
        $request->merge(['start_time'   =>  $time]);
        $request->merge(['end_time'     =>  $time]);  
        $program->fill(Input::all()); 

        $program->save();   
         
        return Redirect::to(route('program'))
                            ->with('flash_alert_notice', 'New Campaign  successfully created!');
    }

    /*
     * Edit Group method
     * @param 
     * object : $category
     * */

    public function edit($id) {
        $program = Program::find($id);
        $page_title     = 'Campaign';
        $page_action    = 'Edit Campaign'; 

         $status         = [ 0=>'Select Reward Type',
                            1=>'Fixed',
                            2=>'Percentage'
                        ];
        return view('packages::program.edit', compact('program','status', 'page_title', 'page_action'));
    }

    public function update(Request $request, $id) {
        $program = Program::find($id);
        
        $time = date('h:i:s A');
        $start_date = $request->start_date;
        $end_date   = $request->end_date;
        
        $timestamp_sd  = strtotime($start_date);
        $timestamp_ed  = strtotime($end_date);

        $request->merge(['start_date'   =>  date('Y-m-d', $timestamp_sd)]); 
        $request->merge(['end_date'     =>  date('Y-m-d', $timestamp_ed)]);
        $request->merge(['start_time'   =>  $time]);
        $request->merge(['end_time'     =>  $time]);  
        $program->fill(Input::all()); 
       
        $program->fill(Input::all()); 
        $program->save();  
       
        return Redirect::to(route('program'))
                        ->with('flash_alert_notice', 'Campaign  successfully updated.');
    }
    /*
     *Delete User
     * @param ID
     * 
     */
    public function destroy($program) { 
        
        Program::where('id',$program)->delete();
        return Redirect::to(route('program'))
                        ->with('flash_alert_notice', 'Campaign  successfully deleted.');
    }

    public function show($id) {
        $program = Program::find($id);
        $page_title     = 'Campaign';
        $page_action    = 'Show Campaign'; 
        $result = $program;

        $trigger_condition = '(
                        CASE 
                        WHEN trigger_condition = 1 THEN "Sign up" 
                        WHEN trigger_condition = 2 THEN "First Transaction"
                        ELSE
                        "Sign up" end) as trigger_condition';

         $reward_type = '(
                        CASE
                        WHEN reward_type = 1 THEN "Fixed" 
                        WHEN reward_type = 2 THEN "Percentage" 
                        ELSE
                        "Fixed" end) as reward_type';
        $promotion_type = '(
                        CASE
                        WHEN promotion_type = 1 THEN "Referral" 
                        WHEN promotion_type = 2 THEN "Bonus" 
                        ELSE
                        "Bonus" end) as promotion_type';

        $status = '(
                        CASE
                        WHEN status = 1 THEN "Active" 
                        WHEN status = 2 THEN "Planned"
                        WHEN status = 3 THEN "Draft" 
                        ELSE
                        "Active" end) as status';
        $customer_type = '(
                        CASE
                        WHEN customer_type = 1 THEN "Public" 
                        WHEN customer_type = 2 THEN "Custom"
                        ELSE
                        "Public" end) as customer_type';

        $program = Program::where('id',$program->id)
                    ->select('*',
                        \DB::raw($customer_type),
                        \DB::raw($trigger_condition),
                        \DB::raw($reward_type),
                        \DB::raw($promotion_type),
                        \DB::raw($status))
                    ->first()
                    ->toArray();  

        return view('packages::program.show', compact( 'result','program','page_title', 'page_action'));

    }

}