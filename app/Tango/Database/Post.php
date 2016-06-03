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
    ];

    public function User()
    {
        return $this->hasOne('App\User', 'id', 'posted_by');
    }
}
