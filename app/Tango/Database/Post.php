<?php

namespace App\Tango\Database;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    //
    protected $table = 'post';

    protected $fillable = [
    	'post_name',
    	'post_content',
    	'post_slug',
    	'category_id',
    	'post_type',
    	'post_id',
    	'posted_by',
        'created_at',
        'updated_at'
    ];

    public $timestamps = true;

    public function User()
    {
        return $this->hasOne('App\User', 'id', 'posted_by');
    }
    public function Category()
    {
        return $this->hasOne('App\Tango\Database\Category', 'id', 'category_id');
    }

    public function Replies()
    {
        return $this->hasOne('App\Tango\Database\Post', 'post_id', 'id');
    }
}
