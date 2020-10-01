<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;


class ApiController extends Controller
{
    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return View
     */
    public function index()
    {
        dd(1);
    }
}