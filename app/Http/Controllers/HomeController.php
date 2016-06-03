<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Tango\Database\Category as Category;
use Auth;

class HomeController extends Controller
{
    //

    public function Home()
    {
    	$category = Category::orderBy('category_place', 'asc')->get();

    	foreach( $category as $cat )
    	{
    		if( $cat['category_place'] == 1 )
    		{
    			$thread = Category::find($cat['id'])->Posts()->orderBy('updated_at', 'desc')->limit(12)->get();
    		}
    	}
    	//die(var_dump($thread));

    	return view('home', ['category' => $category, 'thread' => $thread]);
    }
}
