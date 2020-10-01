<?php
namespace Modules\Admin\Http\Controllers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests;
use Illuminate\Http\Request;
use Modules\Admin\Http\Requests\BannerRequest;
use Modules\Admin\Models\User; 
use Input, Validator, Auth, Paginate, Grids, HTML;
use Form, Hash, View, URL, Lang, Session, DB;
use Route, Crypt, Str;
use App\Http\Controllers\Controller;
use Illuminate\Http\Dispatcher; 
use App\Helpers\Helper;
use Modules\Admin\Models\Roles; 
use Modules\Admin\Models\Banner; 
 

/**
 * Class AdminController
 */
class BannerController extends Controller {
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
        View::share('viewPage', 'Banner');
        View::share('sub_page_title', 'Banner');
        View::share('helper',new Helper);
        View::share('heading','Banner');
        View::share('route_url',route('banner'));

        $this->record_per_page = Config::get('app.record_per_page');
    }

   
    /*
     * Dashboard
     * */

    public function index(Banner $banner, Request $request) 
    { 
        $page_title = 'Banner';
        $sub_page_title = 'Banner';
        $page_action = 'View Banner'; 


        if ($request->ajax()) {
            $id = $request->get('id'); 
            $banner = Banner::find($id); 
            $banner->status = $s;
            $banner->save(); 
            exit();
        }

        // Search by name ,email and group
        $search = Input::get('search');
        if ((isset($search) && !empty($search))) {

            $search = isset($search) ? Input::get('search') : '';
               
            $banners = Banner::where(function($query) use($search) {
                        if (!empty($search)) {
                            $query->Where('title', 'LIKE', "%$search%");
                        }
                        
                    })->Paginate($this->record_per_page);
        } else {
            $banners = Banner::Paginate($this->record_per_page);
        }
         
        
        return view('packages::banner.index', compact('banners', 'page_title', 'page_action','sub_page_title'));
    }

    /*
     * create Group method
     * */

    public function create(Banner $banner) 
    {
         
        $page_title = 'Banner';
        $page_action = 'Create Banner';
 
        $url = '';

        return view('packages::banner.create', compact('url','banner', 'page_title', 'page_action'));
    }

    /*
     * Save Group method
     * */

    public function store(BannerRequest $request, Banner $banner) 
    {  

        
        $photo = $request->file('photo');
        $destinationPath = storage_path('uploads/banner');
        $photo->move($destinationPath, time().$photo->getClientOriginalName());
        $photo_name = time().$photo->getClientOriginalName();
        $request->merge(['photo'=>$photo_name]);
        
        
        $banner = new Banner;
        $banner->title        =  $request->get('title');
        $banner->photo        =  $photo_name; 
	    $banner->url          =  url('storage/uploads/banner/'.$photo_name);
        $banner->description  =  $request->get('description');
        
        $banner->save();   
         
        return Redirect::to(route('banner'))
                            ->with('flash_alert_notice', 'New banner  successfully created !');
        }

    /*
     * Edit Group method
     * @param 
     * object : $banner
     * */

    public function edit($id) {
        $banner = Banner::find($id);
        $page_title = 'Banner';
        $page_action = 'Edit Banner'; 
        $url = $banner->url;
        return view('packages::banner.edit', compact( 'url','banner', 'page_title', 'page_action'));
    }

    public function update(BannerRequest $request,  $id) {
        $banner = Banner::find($id);
       

        $validate_cat = Banner::where('title',$request->get('title'))
                            ->where('id','!=',$banner->id)
                            ->first();
         
        if($validate_cat){
              return  Redirect::back()->withInput()->with(
                'field_errors','The banner name already been taken!'
            );
        } 


        if ($request->file('photo')) {
            $photo = $request->file('photo');
            $destinationPath = storage_path('uploads/banner');
            $photo->move($destinationPath, time().$photo->getClientOriginalName());
            $photo_name = time().$photo->getClientOriginalName();
            $request->merge(['photo'=>$photo_name]);
            $banner->photo        =  $photo_name; 
            $banner->url          =  url('storage/uploads/banner/'.$photo_name);	
        } 

        $banner->title         =  $request->get('title'); 
        $banner->description           =  $request->get('description'); 
         
        $banner->save();    


        return Redirect::to(route('banner'))
                        ->with('flash_alert_notice', ' Banner  successfully updated.');
    }
    /*
     *Delete User
     * @param ID
     * 
     */
    public function destroy($id) {

        Banner::where('id',$id)->delete(); 
        return Redirect::to(route('banner'))
                        ->with('flash_alert_notice', ' Banner  successfully deleted.');
        
    }

    public function show(Banner $banner) {
        
    }

}
