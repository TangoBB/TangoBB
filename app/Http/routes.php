<?php
//use App\Tango\Database\Settings as Settings;
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::group(['as' => 'Index::'], function() {
	Route::get('/', ['as' => '/', 'uses' => 'HomeController@Home']);
	Route::get('/', ['as' => 'Index', 'uses' => 'HomeController@Home']);
});

Route::group(['as' => 'Account::', 'namespace' => 'Account', 'prefix' => 'account'], function() {
	Route::get('login', ['as' => 'LogIn', 'uses' => 'AuthController@LogIn']);
	Route::post('login', ['as' => 'LogIn.Post', 'uses' => 'AuthController@JsonLogIn']);

	Route::get('signup', ['as' => 'SignUp', 'uses' => 'AuthController@SignUp']);
	Route::post('signup', ['as' => 'SignUp.Post', 'uses' => 'AuthController@JsonLogIn']);

	Route::get('logout', ['as' => 'LogOut', 'uses' => 'AuthController@LogOut']);
});

Route::group(['as' => 'Core::', 'prefix' => 'core', 'namespace' => 'Core', 'middleware'=>'setTheme:Core'], function() {
	Route::group(['as' => 'Js::', 'prefix' => 'js', 'namespace' => 'Js'], function() {
		Route::get('render', ['as' => 'RenderJavascript', 'uses' => 'Render@Render']);
	});
});

Route::group(['as' => 'Json::', 'prefix' => 'json'], function() {
	Route::group(['as' => 'Account::', 'prefix' => 'account'], function() {
		Route::post('login', ['as' => 'LogIn', 'uses' => 'Account\AuthController@JsonLogIn']);

		Route::post('signup', ['as' => 'SignUp', 'uses' => 'Account\AuthController@JsonSignUp']);
	});
});

Route::group(['as' => 'Forum::'], function() {
	Route::get('category/{slug}.{id}', ['as' => 'Category', 'uses' => 'Forum\Category@Index']);
});
