<?php

namespace App\Tango\Database;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //
    protected $table = 'category';

    protected $fillable = [
    	'category_name',
    	'category_slug',
    	'category_description',
    	'category_color',
    	'category_place',
    	'allowed_usergroup'
    ];

    public $timestamps = true;

    public function Posts()
    {
        return $this->hasMany('App\Tango\Database\Post', 'category_id', 'id');
    }
}
