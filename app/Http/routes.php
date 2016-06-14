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

	Route::group(['as' => 'Change::', 'prefix' => 'change'], function() {
		Route::get('password', ['as' => 'Password', 'uses' => 'Details@ChangePassword']);
		Route::post('password', ['as' => 'Password.Post', 'uses' => 'Details@ChangePassword']);

		Route::get('email', ['as' => 'Email', 'uses' => 'Details@ChangeEmail']);
		Route::post('email', ['as' => 'Email.Post', 'uses' => 'Details@ChangeEmail']);
	});
});

Route::group(['as' => 'Core::', 'prefix' => 'core', 'namespace' => 'Core', 'middleware'=>'setTheme:Core'], function() {
	Route::group(['as' => 'Js::', 'prefix' => 'js', 'namespace' => 'Js'], function() {
		Route::get('render.js', ['as' => 'RenderJavascript', 'uses' => 'Render@Render']);
	});
});


//JSON Requests
Route::group(['as' => 'Json::', 'prefix' => 'json'], function() {
	Route::group(['as' => 'Account::', 'prefix' => 'account'], function() {
		Route::post('login', ['as' => 'LogIn', 'uses' => 'Core\Ajax\AuthAjax@LogIn']);

		Route::post('signup', ['as' => 'SignUp', 'uses' => 'Core\Ajax\AuthAjax@SignUp']);

		Route::group(['as' => 'Change::', 'prefix' => 'change'], function() {
			Route::post('password', ['as' => 'Password', 'uses' => 'Core\Ajax\Details@ChangePassword']);

			Route::post('email', ['as' => 'Email', 'uses' => 'Core\Ajax\Details@ChangeEmail']);
		});
	});

	Route::group(['as' => 'Forum::', 'prefix' => 'forum'], function() {
		Route::group(['as' => 'Thread::', 'prefix' => 'thread'], function() {
			Route::post('create/{id}', ['as' => 'Create', 'uses' => 'Core\Ajax\Forum\Category@Post']);

			Route::post('reply/{id}', ['as' => 'Reply', 'uses' => 'Core\Ajax\Forum\Thread@Reply']);

			Route::get('delete/{id}', ['as' => 'DeleteThread', 'uses' => 'Core\Ajax\Forum\Thread@Delete']);//Delete posts including all relations.

			Route::post('editpost/{id}', ['as' => 'EditPost', 'uses' => 'Core\Ajax\Forum\Thread@Edit']);//Edit posts.

			Route::get('stick/{id}', ['as' => 'Stick', 'uses' => 'Core\Ajax\Forum\Thread@Stick']);

			Route::get('lock/{id}', ['as' => 'Stick', 'uses' => 'Core\Ajax\Forum\Thread@Lock']);
		});
	});
});

//Forum
Route::group(['as' => 'Forum::'], function() {
	//Category
	Route::group(['as' => 'Category::'], function() {
		Route::get('category/{slug}.{id}', ['as' => 'Category', 'uses' => 'Forum\Category@Index']);

		Route::get('/category/{slug}.{id}/post', ['as' => 'Post', 'uses' => 'Forum\Category@Post']);
		Route::post('/category/{slug}.{id}/post', ['as' => 'Post.Post', 'uses' => 'Forum\Category@Post']);
	});

	//Thread
	Route::group(['as' => 'Thread::'], function() {
		Route::get('thread/{slug}.{id}', ['as' => 'Thread', 'uses' => 'Forum\Thread@Index']);
		Route::post('thread/{slug}.{id}', ['as' => 'Thread.Post', 'uses' => 'Forum\Thread@Index']);

		Route::get('thread/{id}/delete', ['as' => 'Delete', 'uses' => 'Forum\Thread@Delete']);

		Route::get('thread/{id}/edit', ['as' => 'Edit', 'uses' => 'Forum\Thread@Edit']);
		Route::post('thread/{id}/edit', ['as' => 'Edit.Post', 'uses' => 'Forum\Thread@Edit']);

		Route::get('thread/{id}/reply', ['as' => 'Reply', 'uses' => 'Forum\Thread@Reply']);
		Route::post('thread/{id}/reply', ['as' => 'Reply.Post', 'uses' => 'Forum\Thread@Reply']);

		Route::get('thread/{id}/stick', ['as' => 'Stick', 'uses' => 'Forum\Thread@Stick']);

		Route::get('thread/{id}/lock', ['as' => 'Lock', 'uses' => 'Forum\Thread@Lock']);
	});
});
