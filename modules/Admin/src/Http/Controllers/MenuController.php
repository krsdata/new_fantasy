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
 

/**
 * Class MenuController
 */
class MenuController extends Controller {
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
        View::share('viewPage', 'Menu');
        View::share('sub_page_title', 'Menu');
        View::share('helper',new Helper);
        View::share('heading','Menu');
        View::share('route_url',route('menu'));

        $this->record_per_page = Config::get('app.record_per_page');
    }

   
    /*
     * Dashboard
     * */

    public function index(Menu $menu, Request $request) 
    { 
        $page_title = 'Menu';
        $sub_page_title = 'Menu';
        $page_action = 'View Menu'; 


        if ($request->ajax()) {
            $id = $request->get('id'); 
            $menu = Menu::find($id); 
            $menu->status = $s;
            $menu->save();
            echo $s;
            exit();
        }

        // Search by name ,email and group
        $search = Input::get('search');
        $status = Input::get('status');
        if ((isset($search) && !empty($search))) {

            $search = isset($search) ? Input::get('search') : '';
               
            $menu = Menu::with('childs')->where(function($query) use($search,$status) {
                        if (!empty($search)) {
                            $query->Where('title', 'LIKE', "%$search%");
                        }
                        
                    })->where('parent_id',0)->Paginate($this->record_per_page);
        } else {
            $menu = Menu::where('parent_id',0)
                        ->with('childs')
                        ->Paginate($this->record_per_page);
        }
         
        $allMenu = Menu::pluck('title','id')->all(); 
        
        return view('packages::menu.index', compact('menu', 'page_title', 'page_action','sub_page_title','allMenu'));
    }

    /*
     * create Group method
     * */

    public function create(Menu $menu) 
    {
         
        $page_title = 'Menu';
        $page_action = 'Create Menu';
        $allMenu  = Menu::pluck('title','id');

        return view('packages::menu.create', compact('menu','menu', 'page_title', 'page_action','allMenu'));
    }

    /*
     * Save Group method
     * */

    public function store(Request $request, Menu $menu) 
    {  
        $pid = $request->parent_id ? $request->parent_id : 0;
        
        $routeCollection = \Route::getRoutes();
        foreach ($routeCollection as $route) {
            if($route->getPrefix()=='/admin'){
               //  $data['uri'][]       = $route->uri();
                 $data['getName'][]   = $route->getName();  
               //  $data['getActionMethod'][] = $route->getActionMethod();           
            }
       
        }
        $is_route_exist = in_array($request->route_name, $data['getName']);
        
        if($is_route_exist){
            $menu = new Menu;
            $menu->title            =  $request->get('title');
            $menu->route_name       =  $request->get('route_name');
            $menu->action           =  $request->get('action');
            $menu->url              =  $request->get('url');
            $menu->display_order    =  $request->get('display_order');
            $menu->parent_id        =  $pid;
            $menu->save();  
            return Redirect::to(route('menu'))
                            ->with('flash_alert_notice', 'new Menu  successfully created !');
        }    
        return Redirect::to(route('menu'))
                            ->with('flash_alert_notice', 'Route name does not exit!');
       
        }

    /*
     * Edit Group method
     * @param 
     * object : $menu
     * */

    public function edit($id) {
        $menu = Menu::find($id);
        $page_title = 'Menu';
        $page_action = 'Edit Menu'; 

        $allMenu  = Menu::pluck('title','id');

        
        return view('packages::menu.edit', compact( 'menu','allMenu', 'page_title', 'page_action'));
    }

    public function update(Request $request, $id) {
        $menu = Menu::find($id);
        $pid = $request->parent_id ? $request->parent_id : 0;
        $menu                = Menu::find($menu->id);
        $menu->title         =  $request->get('title');
        $menu->parent_id     =  $pid;
      
        $menu->save();   


        return Redirect::to(route('menu'))
                        ->with('flash_alert_notice', ' Category  successfully updated.');
    }
    /*
     *Delete User
     * @param ID
     * 
     */
    public function destroy($id) {
        Menu::where('id',$id)->delete(); 
        return Redirect::to(route('menu'))
                        ->with('flash_alert_notice', ' Category  successfully deleted.');
  
    } 

}
