<?php

namespace App\Http\Controllers\Account;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\user as User;

use Validator;
use Auth;
use Hash;
use DB;

class AuthController extends Controller
{

    //Private functions.
    private function LogRequest(Request $request)
    {
    	//Logging In
    	$validator = Validator::make($request->all(), [
    		'username' => 'bail|required|exists:users,name|AuthPermission',
    		'password' => 'bail|required|AuthCheck',
            'remember_me' => 'sometimes|required'
    		]);
    	if( $validator->fails() )
    	{
    		return $validator;
    	}
    	else
    	{
    		$user     = User::where('name', '=', $request->only('username')['username'])->first();
            $remember = (isset($request->only('remember_me')['remember_me']))? true : false;
    		Auth::login($user, $remember);
    		return true;
    	}
    }

    private function SignUpRequest(Request $request)
    {
    	//Signing Up
    	$validator = Validator::make($request->all(), [
    		'username' => 'bail|required|unique:users,name',
    		'email' => 'bail|required|email|unique:users,email',
    		'password' => 'bail|required|same:confirm_password',
    		'confirm_password' => 'bail|required'
    		]);
    	if( $validator->fails() )
    	{
    		return $validator;
    	}
    	else
    	{
    		$username = $request->only('username')['username'];
    		$email    = $request->only('email')['email'];
    		$password = Hash::make($request->only('password')['password']);
    		/*User::insert(
    			['name' => $username, 'email' => $email, 'password' => $password]
    			);*/
            DB::table('users')->insert(
                ['name' => $username, 'email' => $email, 'password' => $password, 'excluded_permissions' => '']
                );
    		return true;
    	}
    }

    public function LogIn(Request $request)
    {
    	if( Auth::check() ) { return abort(404); }

    	if( $request->isMethod('post') )
    	{
    		$validator = $this->LogRequest($request);
    		if( !is_object($validator) )
    		{
    			return redirect()->route('Index::Index');
    		}
    		else
    		{
    			//die(var_dump($validator->errors()));
    			return redirect()->route('Account::LogIn')->withErrors($validator);
    		}
    	}

    	return view('account.login');
    }

    public function SignUp(Request $request)
    {
    	if( Auth::check() ) { return abort(404); }

    	if( $request->isMethod('post') )
    	{
    		$validator = $this->SignUpRequest($request);
    		//$validator = $this->LogRequest($request);
    		if( !is_object($validator) )
    		{
    			return redirect()->route('Index::Index');
    		}
    		else
    		{
    			//die(var_dump($validator->errors()));
    			return redirect()->route('Account::SignUp')->withErrors($validator);
    		}
    	}

    	return view('account.signup');
    }

    public function LogOut()
    {
    	if( !Auth::check() ) { return abort(404); }

    	Auth::logout();
    	return redirect()->route('Index::Index')->with('success', trans('messages.logout.success'));
    }
}
