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
use Modules\Admin\Models\ContestType;  
use Response; 
use Modules\Admin\Http\Requests\ContestTypeRequest;
/**
 * Class AdminController
 */
class ContestTypeController extends Controller {
    /**
     * @var  Repository
     */

    /**
     * Displays all admin.
     *
     * @return \Illuminate\View\View
     */
    public function __construct(ContestType $contestType) { 
        $this->middleware('admin');
        View::share('viewPage', 'Contest Type');
        View::share('sub_page_title', 'Contest Type');
        View::share('helper',new Helper);
        View::share('heading','Contest Type');
        View::share('route_url',route('contestType')); 
        $this->record_per_page = Config::get('app.record_per_page'); 
    }

   
    /*
     * Dashboard
     * */

    public function index(ContestType $contestType, Request $request) 
    { 
        $page_title = 'Contest Type';
        $sub_page_title = 'View Contest Type';
        $page_action = 'View Contest Type'; 

 
        // Search by name ,email and group
        $search = Input::get('search');
        $status = Input::get('status');
        if ((isset($search) && !empty($search))) {

            $search = isset($search) ? Input::get('search') : '';
               
            $contestType = ContestType::where(function($query) use($search,$status) {
                        if (!empty($search)) {
                            $query->Where('contest_type', 'LIKE', "%$search%");
                        }
                        
                    })->Paginate($this->record_per_page);
        } else {
            $contestType = ContestType::Paginate($this->record_per_page);
        }
         
        
        return view('packages::contestType.index', compact('contestType','page_title', 'page_action','sub_page_title'));
    }

    /*
     * create ContestType
     * */

    public function create(ContestType $contestType) 
    {
        $page_title     = 'Contest Type';
        $page_action    = 'Create Contest Type'; 
        

        return view('packages::contestType.create', compact( 'contestType','page_title', 'page_action'));
    }

    

    /*
     * Save Group method
     * */

    public function store(ContestTypeRequest $request, ContestType $contestType) 
    {   
        $contestType->fill(Input::all()); 
        $contestType->save();   
        
        \DB::table('create_contests')->where('contest_type',$contestType->id)
                ->update(['sort_by'=>$contestType->sort_by]);

        return Redirect::to(route('contestType'))
                            ->with('flash_alert_notice', 'New Contest Type  successfully created!');
    }

    /*
     * Edit Group method
     * @param 
     * object : $category
     * */

    public function edit($id) {
        $contestType = ContestType::find($id);
        $page_title     = 'Contest Type';
        $page_action    = 'Edit Contest Type'; 
        
        return view('packages::contestType.edit', compact('contestType', 'page_title', 'page_action'));
    }

    public function update(Request $request, $id) {
        $contestType = ContestType::find($id);
        $contestType->fill(Input::all()); 
        $contestType->save(); 

        \DB::table('create_contests')->where('contest_type',$contestType->id)
                ->update(['sort_by'=>$contestType->sort_by]);

        return Redirect::to(route('contestType'))
                        ->with('flash_alert_notice', 'Contest Type  successfully updated.');
    }
    /*
     *Delete User
     * @param ID
     * 
     */
    public function destroy($id) { 
        
        ContestType::where('id',$id)->delete();
        return Redirect::to(route('contestType'))
                        ->with('flash_alert_notice', 'Contest Type  successfully deleted.');
    }

    public function show($id) {
	
try{
 
        $contestType = ContestType::find((int)$id);
 	if(!$contestType){
		return Redirect::to(route('contestType'));
	}
        $page_title     = 'Contest Type';
        $page_action    = 'Show Contest Type'; 
        $result = $contestType;
        $contestType = ContestType::where('id',$contestType->id)->select(['contest_type','description','max_entries','cancellable','created_at'])->first()->toArray();
    
 } catch(Exception $e){
 	 
}
	   
        return view('packages::contestType.show', compact( 'result','contestType','page_title', 'page_action'));

    }

}
