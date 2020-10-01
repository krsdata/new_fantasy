<?php

declare(strict_types=1);

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use Input, Str;
use Modules\Admin\Helpers\Helper as Helper;
use Modules\Admin\Http\Requests\PageRequest;
use Modules\Admin\Models\Category;
use Modules\Admin\Models\Meta;
use Modules\Admin\Models\Pages;
use Modules\Admin\Models\User;
use Route;
use View;

/**
 * Class AdminController
 */
class PageController extends Controller
{
    /**
     * @var  Repository
     */

    /**
     * Displays all admin.
     *
     * @return \Illuminate\View\View
     */
    public function __construct()
    {
        $this->middleware('admin');
        View::share('viewPage', 'page');
        View::share('helper', new Helper);
        View::share('route_url', route('content'));
        View::share('heading', 'Content');
        $this->record_per_page = Config::get('app.record_per_page');
    }

    public function error(Pages $page, Request $request){

        $page_title  = 'Error';
        $page_action = 'View Error';
      
        return view('packages::errors.404', compact('page_title', 'page_action'));

    }
    /*
     * Dashboard
     * */

    public function index(Pages $page, Request $request)
    {
        $page_title  = 'Page';
        $page_action = 'View Page';

        // Search by name ,email and group
        $search = Input::get('search');
        $status = Input::get('status');

        if ((isset($search) && !empty($search)) or  (isset($status) && !empty($status))) {
            $search = isset($search) ? Input::get('search') : '';

            $page = Pages::where(function ($query) use ($search,$status) {
                if (!empty($search)) {
                    $query->Where('title', 'LIKE', "%$search%");
                }
            })->Paginate($this->record_per_page);
        } else {
            $page = Pages::orderBy('id', 'desc')->Paginate(10);
        }

        $js_file = ['common.js','bootbox.js','formValidate.js'];

        return view('packages::pages.index', compact('js_file', 'page', 'page_title', 'page_action'));
    }

    /*
     * create  method
     * @var Pages $result
     */

    public function create(Pages $page)
    {
        $page_title  = 'Page';
        $page_action = 'Create Page';

        return view('packages::pages.create', compact('page', 'page_title', 'page_action'));
    }

    /*
     * Save method
     * */

    public function store(PageRequest $request, Pages $page)
    {
        $page = Pages::firstOrNew(['slug' => $request->get('slug')]);

        if ($request->file('images')) {
            $photo           = $request->file('images');
            $destinationPath = storage_path('pages/');
            $photo->move($destinationPath, time() . $photo->getClientOriginalName());
            $banner_image1  = time() . $photo->getClientOriginalName();
            $page->images   =   $banner_image1;
        }
        // Page input


        if (empty($request->get('slug'))) {
            $page->slug = strtolower(Str::slug($request->get('title')));


        } else {
            $page->slug = strtolower(Str::slug($request->get('slug')));
        }

        if (empty($request->get('meta_title'))) {
            $page->meta_title  =  implode(' ', array_slice(explode(' ', $request->get('title')), 0, 7));
        }

        if (empty($request->get('meta_description'))) {
            $page->meta_description  = implode(' ', array_slice(explode(' ', $request->get('page_content')), 0, 80));
        }

        if (empty($request->get('url'))) {
            $page->url =strtolower(Str::slug($request->get('title')));
        } else {
            $page->url = strtolower(Str::slug($request->get('url')));
        }



        foreach ($request->only('title', 'page_content', 'meta_key') as $key => $value) {
            $page->$key     =   $value;
        }
        $page->save();

        return Redirect::to('admin/content')
           ->with('flash_alert_notice2', 'Page was successfully created !');
    }


    public function meta($obj)
    {
        $content = Meta::where('page_id', $obj->id)->first();

        if (!$content) {
            return $obj;
        }
        $obj->meta_title       = $content->meta_title;
        $obj->meta_key         = $content->meta_key;
        $obj->meta_description = $content->meta_description;
        $obj->url              = $content->url;
        $obj->slug             = $content->slug;

        return $obj;
    }
    /*
     * Edit Group method
     * @param
     * object : $category
     * */

    public function edit(Request $request,$id)
    {   $page = Pages::find($id);
        $page_title  = 'Edit Page';
        $page_action = 'Show page';
        $page        = $this->meta($page);

        return view('packages::pages.edit', compact('page', 'page_title', 'page_action'));
    }

    public function update(Request $request, $id)
    {
        $page = Pages::firstOrNew(['slug' => $request->get('slug'),'id'=>$id]);
        //$page = Pages::find($id);
        if ($request->file('images')) {
            $photo           = $request->file('images');
            $destinationPath = storage_path('pages/');
            $photo->move($destinationPath, time() . $photo->getClientOriginalName());
            $banner_image1  = time() . $photo->getClientOriginalName();
            $page->images   =   $banner_image1;
        }

        if (empty($request->get('slug'))) {
            $page->slug = strtolower(Str::slug($request->get('title')));
        } else {
            $page->slug = strtolower(Str::slug($request->get('slug')));
        }

        if (empty($request->get('meta_title'))) {
            $page->meta_title  = implode(' ', array_slice(str_word_count($request->get('title'), 1), 0, 7));
        }

        if (empty($request->get('meta_description'))) {
            $page->meta_description  = implode(' ', array_slice(explode(' ', $request->get('page_content')), 0, 80));
        }

        if (empty($request->get('url'))) {
            $page->url = Str::slug($request->get('title'));
        } else {
            $page->url = Str::slug($request->get('url'));
        }



        foreach ($request->only('title', 'page_content', 'meta_key') as $key => $value) {
            $page->$key     =   $value;
        }
        $page->save();


        return Redirect::to(route('content'))
            ->with('flash_alert_notice', 'Page was successfully updated!');
    }
    /*
     *Delete User
     * @param ID
     *
     */
    public function destroy(Request $request,$id)
    {
        Pages::where('id', $id)->delete();

        return Redirect::to(route('content'))
            ->with('flash_alert_notice', 'Page was successfully deleted!');
    }

    public function show($id)
    {
        $page_title  = 'Page';
        $page_action = 'Show page';

        $page = Pages::find($id)->toArray();
 

            

        return view('packages::pages.show', compact( 'id','page', 'page_title', 'page_action'));
    }
}
