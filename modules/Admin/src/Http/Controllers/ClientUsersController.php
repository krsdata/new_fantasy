<?php
namespace Modules\Admin\Http\Controllers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests;
use Illuminate\Http\Request;
use Modules\Admin\Http\Requests\UserRequest;
use Modules\Admin\Models\User; 
use Input,Validator,Auth,Paginate;
use Grids,HTML,Form,Hash,View,URL;
use Lang,Session,DB,Route,Crypt;
use App\Http\Controllers\Controller;
use Illuminate\Http\Dispatcher; 
use App\Helpers\Helper;
use Modules\Admin\Models\Roles; 
 

/**
 * Class AdminController
 */
class ClientUsersController extends Controller {
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
        View::share('viewPage', 'Customer');
        View::share('helper',new Helper);
        View::share('heading','Customer');
        View::share('route_url',route('clientuser'));

        $this->record_per_page = Config::get('app.record_per_page');
    }

    protected $users;

    /*
     * Dashboard
     * */

    public function index(User $user, Request $request) 
    { 
        
        $page_title = 'Customer';
        $page_action = 'Customer'; 
        if ($request->ajax()) { 
            $status = $request->get('status');
            $user = User::where('role_type','>=',$request->user()->role_type)->find($id);
            $s = ($status == 1) ? $status = 0 : $status = 1;
            $user->status = $s;
            $user->save();
            echo $s;
            exit();
        }
        // Search by name ,email and group
        $search = Input::get('search');
        $status = Input::get('status');
        $role_type = Input::get('role_type'); 
        if ((isset($search) && !empty($search)) OR  (isset($status) && !empty($status)) or !empty($role_type)) {

            $search = isset($search) ? Input::get('search') : '';
            $mobile_number = $request->mobile_number;   
            $users = User::where(function($query) use($mobile_number,$search,$status,$role_type) {
                
                        if (!empty($search)) {
                            $query->Where('first_name', 'LIKE', "%$search%") 
                                    ->OrWhere('email', 'LIKE', "%$search%");
                        }
                        if (!empty($status)) {
                            $status =  ($status=='active')?1:0;
                            $query->Where('status',$status);
                        }
                        if (!empty($mobile_number)) {
                            $query->where('mobile_number', 'LIKE', "%$mobile_number%");
                        }
                        if (!empty($role_type)) { 
                            $query->Where('role_type',$role_type);
                        }
                    })->where('role_type',3)
                            ->Paginate($this->record_per_page);
        } else {
            $users = User::orderBy('id','desc')
                            ->where('role_type',3)->Paginate(15);
            
            $users->transform(function($item,$key){

                $wallets = \DB::table('wallets')
                            ->where('user_id',$item->id)
                            ->get();
                $item->wallets = $wallets;

                return $item;

            });
        }
        
        $roles = Roles::all();

        $js_file = ['common.js','bootbox.js','formValidate.js'];
        return view('packages::users.index', compact('js_file','roles','status','users', 'page_title', 'page_action','role_type'));
    }

    /*
     * create Group method
     * */

    public function create(User $user) 
    {
        $page_title = 'Customer';
        $page_action = 'Create Customer';
        $roles = Roles::all();
        $role_id = null;
        $js_file = ['common.js','bootbox.js','formValidate.js'];
        return view('packages::users.createClient', compact('js_file','role_id','roles', 'user', 'page_title', 'page_action', 'groups'));
    }

    /*
     * Save Group method
     * */

    public function store(UserRequest $request, User $user) {
        $user->fill(Input::all());
        $user->password = Hash::make($request->get('password'));
        $user->role_type = $request->get('role'); 
        $user->save();
        $js_file = ['common.js','bootbox.js','formValidate.js'];
        return Redirect::to(route('clientuser'))
                            ->with('flash_alert_notice', 'New user  successfully created.');
        }

    /*
     * Edit Group method
     * @param 
     * object : $user
     * */

    public function edit(User $user) {

        $page_title = 'Customer';
        $page_action = 'Show Customer';
        $role_id = $user->role_type;
        $roles = Roles::all();
        $js_file = ['common.js','bootbox.js','formValidate.js'];
        return view('packages::clientuser.edit', compact('js_file','role_id','roles','user', 'page_title', 'page_action'));
    }

    public function update(Request $request, User $user) {
        
        $user->fill(Input::all());
        $user->password = Hash::make($request->get('password'));

        $validator_email = User::where('email',$request->get('email'))
                            ->where('id','!=',$user->id)->first();
        if($validator_email) {
            if($validator_email->id==$user->id)
            {
                $user->save();
            }else{
                  return  Redirect::back()->withInput()->with(
                    'field_errors','The Email already been taken!'
                 );
                 
            }
        }else{
           $user->save(); 
        }

       
        return Redirect::to(route('clientuser'))
                        ->with('flash_alert_notice', 'User   successfully updated.');
    }
    /*
     *Delete User
     * @param ID
     * 
     */
    public function destroy(User $user) {
        
        User::where('id',$user->id)->delete();

        return Redirect::to(route('clientuser'))
                        ->with('flash_alert_notice', 'User  successfully deleted.');
    }

    public function show(User $user) {
        
    }

}
