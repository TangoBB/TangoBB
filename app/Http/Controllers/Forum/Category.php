<?php

namespace App\Http\Controllers\Forum;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Tango\Database\Category as Cate;

class Category extends Controller
{
    //
    public function Index($slug, $id)
    {
    	$category = Cate::where([
    		['id', '=', $id],
    		['category_slug', '=', $slug]
    		])->first();

    	if( !empty($category) )
    	{
    		$categories = Cate::orderBy('category_place', 'asc')->get();
    		$threads    = $category->Posts()->where('post_type', '=', 1)->orderBy('updated_at', 'desc')->paginate('12');
    		return view('forum.category', ['categories' => $categories, 'selected' => $category, 'threads' => $threads]);
    	}
    	else
    	{
    		return abort(404);
    	}
    }
}
