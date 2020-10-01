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
use Modules\Admin\Models\ErrorLog;
use Response;  
/**
 * Class AdminController
 */
class ErrorLogController extends Controller {
    /**
     * @var  Repository
     */

    /**
     * Displays all admin.
     *
     * @return \Illuminate\View\View
     */
    public function __construct(ErrorLog $errorLog) { 
        $this->middleware('admin');
        View::share('viewPage', 'ErrorLog');
        View::share('sub_page_title', 'ErrorLog');
        View::share('helper',new Helper);
        View::share('heading','ErrorLog');
        View::share('route_url',route('errorLog')); 
        $this->record_per_page = Config::get('app.record_per_page'); 
    }

   
    /*
     * Dashboard
     * */

    public function index(ErrorLog $errorLog, Request $request) 
    { 
        $page_title = 'ErrorLog';
        $sub_page_title = 'View ErrorLog';
        $page_action = 'View ErrorLog'; 


        if ($request->ajax()) {
            $id = $request->get('id'); 
            $category = ErrorLog::find($id); 
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
               
            $errorLog = ErrorLog::where(function($query) use($search,$status) {
                        if (!empty($search)) {
                           $query->Where('error_type', 'LIKE', "%$search%");
                           $query->orWhere('message', 'LIKE', "%$search%");
                           $query->orWhere('url', 'LIKE', "%$search%");
                        }
                        
                    })->orderBy('id','desc')->Paginate($this->record_per_page);
        } else {
            $errorLog = ErrorLog::orderBy('id','desc')->Paginate($this->record_per_page);
        }
         
        
        return view('packages::errorLog.index', compact('errorLog','page_title', 'page_action','sub_page_title'));
    }

    /*
     * create Group method
     * */

    public function create(ErrorLog $errorLog) 
    {
        $page_title     = 'ErrorLog';
        $page_action    = 'Create ErrorLog';
        $program       = ErrorLog::all(); 
        

        return view('packages::errorLog.create', compact( 'errorLog','page_title', 'page_action'));
    }

    

    /*
     * Save Group method
     * */

    public function store(ErrorRequest $request, ErrorLog $errorLog) 
    {   
        $program->fill(Input::all()); 
        $program->save();   
         
        return Redirect::to(route('program'))
                            ->with('flash_alert_notice', 'New program  successfully created!');
    }

    /*
     * Edit Group method
     * @param 
     * object : $category
     * */

    public function edit($id) {
        $errorLog = ErrorLog::find($id);
        $page_title     = 'ErrorLog';
        $page_action    = 'Edit ErrorLog'; 
        $status         = [
                            
                        ];
        return view('packages::errorLog.edit', compact('errorLog','status', 'page_title', 'page_action'));
    }

    public function update(Request $request, $id) {
        $errorLog = ErrorLog::find($id);
        $errorLog->fill(Input::all()); 
        $errorLog->save();  
        return Redirect::to(route('errorLog'))
                        ->with('flash_alert_notice', 'errorLog  successfully updated.');
    }
    /*
     *Delete User
     * @param ID
     * 
     */
    public function destroy($errorLog) { 
        
        ErrorLog::where('id',$errorLog)->delete();
        return Redirect::to(route('errorLog'))
                        ->with('flash_alert_notice', 'errorLog  successfully deleted.');
    }

    public function show($id) {
        $program = ErrorLog::find($id);
        $page_title     = 'ErrorLog';
        $page_action    = 'Show ErrorLog'; 
        $result = $program;
        $program = ErrorLog::where('id',$program->id)->first()->toArray();
        
        return view('packages::errorLog.show', compact( 'result','program','page_title', 'page_action'));

    }

}