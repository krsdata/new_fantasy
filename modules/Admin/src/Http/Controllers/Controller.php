<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;
use View;

class Controller extends BaseController
{
	use DispatchesJobs, ValidatesRequests;
 	function __construct(){
 		View::share('WebsiteTitle','Plug11');
    }
}
