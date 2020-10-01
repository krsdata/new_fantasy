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
use Modules\Admin\Models\Wallets;

/**
 * Class MenuController
 */
class WalletsController extends Controller {
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
        View::share('viewPage', 'Wallets');
        View::share('sub_page_title', 'Wallets');
        View::share('helper',new Helper);
        View::share('heading','Wallets');
        View::share('route_url',route('wallets'));

        $this->record_per_page = Config::get('app.record_per_page');
    }


    /*
     * Dashboard
     * */

    public function index(Wallets $wallets, Request $request)
    {
        $page_title = 'Wallets';
        $sub_page_title = 'Wallets';
        $page_action = 'View Wallets'; 
        if ($request->ajax()) {
            $id = $request->get('id');
            $wallets = Wallets::find($id);
            $wallets->status = $s;
            $wallets->save();
            echo $s;
            exit();
        }

        // Search by name ,email and group
        $search = Input::get('search');
        $status = Input::get('status');
        $user = User::where('email','LIKE',"%$search%")
                            ->orWhere('first_name','LIKE',"%$search%")
                            ->orWhere('phone','LIKE',"%$search%")
                            ->get('id')->pluck('id');

        if ((isset($search) && !empty($search))) { 

            $wallets = Wallets::where(function($query) use($search,$status,$user) {
                        if (!empty($search)) {
                             $query->whereIn('user_id', $user);
                             $query->orWhere('payment_type_string', $search);
                         } 
                    })->Paginate($this->record_per_page);
             $wallets->transform(function($item,$key){
                  
                    $user = User::find($item->user_id);
                    if($user){
                        $item->name = $user->name;
                        $item->email = $user->email;
                        $item->phone = $user->phone;
                    } 
                   
                    return $item; 
            });
        } else {
            $wallets = Wallets::whereHas('user')
                            ->orderBy('user_id','DESC')
                            ->orderBy('amount','DESC')
                            ->Paginate($this->record_per_page);
                                                    
          //  dd($wallets);

            $wallets->transform(function($item,$key){
                  
                    $user = User::find($item->user_id);
                    if($user){
                        $item->name = $user->name;
                        $item->email = $user->email;
                        $item->phone = $user->phone;
                    } 
                   
                    return $item; 
            });

        } 

        $table_cname = \Schema::getColumnListing('wallets');
        
        $except = ['validate_user','id','created_at','updated_at','usable_amount_validation','prize_distributed_id','payment_type','bonus_amount','referal_amount','prize_amount','deposit_amount','usable_amount','total_withdrawal_amount'];
        $data = [];
        $tables[] = 'name';
        $tables[] = 'email';
        foreach ($table_cname as $key => $value) {

           if(in_array($value, $except )){
                continue;
           }
             
          
              
            $tables[] = $value;
        }
        
        return view('packages::wallets.index', compact('wallets', 'page_title', 'page_action','sub_page_title','tables'));
    }

    /*
     * create Group method
     * */

    public function create(Wallets $wallets)
    {

        $page_title     = 'Wallets';
        $page_action    = 'Create Wallets';
        $table_cname = \Schema::getColumnListing('wallets');
        $except = ['id','created_at','updated_at','validate_user','bonus_amount','referal_amount','prize_amount','deposit_amount','usable_amount','usable_amount_validation','total_withdrawal_amount','prize_distributed_id'];
        $data = [];
        foreach ($table_cname as $key => $value) {

           if(in_array($value, $except )){
                continue;
           }
             $tables[] = $value;
        }

        return view('packages::wallets.create', compact('wallets', 'page_title', 'page_action','tables'));
    }

    /*
     * Save Group method
     * */

    public function store(Request $request, Wallets $wallets)
    {
        $data = [];
        $table_cname = \Schema::getColumnListing('wallets');
        $except = ['id','created_at','updated_at','_token','_method'];
        $data = [];
        foreach ($table_cname as $key => $value) {

           if(in_array($value, $except )){
                continue;
           }
            if($request->$value!=null){
                $wallets->$value = $request->$value;
           }
        }
        
        $wallets->save();
        return Redirect::to(route('wallets'))
                            ->with('flash_alert_notice', 'Wallets successfully created !');
        }

    /*
     * Edit Group method
     * @param
     * object : $menu
     * */

    public function edit($id) {
        $wallets = Wallets::find($id);
        $page_title = 'Wallets';
        $page_action = 'Wallets';

        $table_cname = \Schema::getColumnListing('wallets');
        $except = ['id','created_at','updated_at','bonus_amount','referal_amount','prize_amount','deposit_amount','usable_amount','usable_amount_validation','total_withdrawal_amount','prize_distributed_id'];
        $data = [];
        foreach ($table_cname as $key => $value) {

           if(in_array($value, $except )){
                continue;
           }
             $tables[] = $value;
        }

        return view('packages::wallets.edit', compact( 'wallets', 'page_title','page_action', 'tables'));
    }

    public function update(Request $request, $id) {

        $wallets = Wallets::find($id);
        $data = [];
        $table_cname = \Schema::getColumnListing('wallets');
        $except = ['id','created_at','updated_at','_token','_method'];
        $data = [];
        foreach ($table_cname as $key => $value) {

           if(in_array($value, $except )){
                continue;
           }
            if($request->$value){
                $wallets->$value = $request->$value;
           }
        }

        $wallets->save();

        return Redirect::to(route('wallets'))
                        ->with('flash_alert_notice', ' Wallets  successfully updated.');
    }
    /*
     * Delete User
     * @param ID
     *
     */
    public function destroy($id) {
        #PrizeDistribution::where('id',$id)->delete();
        return Redirect::to(route('wallets'))
                        ->with('flash_alert_notice', ' wallets  successfully deleted.');

    }

}
