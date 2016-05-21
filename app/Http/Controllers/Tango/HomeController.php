<?php

namespace App\Http\Controllers\Tango;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    //

    public function Home()
    {
    	return view('home', ['msg' => 'hi']);
    }
}
