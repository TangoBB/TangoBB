<?php

namespace App\Http\Controllers\Core\Ajax;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Auth;
use Validator;
use Hash;

use App\User as User;

class Details extends Controller
{
    //Controller functions.
	private function ChangePasswordRequest(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'old_password' => 'bail|required',
			'new_password' => 'bail|required|same:confirm_password',
			'confirm_password' => 'bail|required'
			]);

		if( $validator->fails() )
		{
			return $validator;
		}
		else
		{
			$user = Auth::user();

			if( Hash::check($request->only('old_password')['old_password'], $user['password']) )
			{
				$user->update([
					'password' => Hash::make($request->only('new_password')['new_password'])
					]);
				return 1;
			}
			else
			{
				return 0;
			}

		}
	}

	private function ChangeEmailRequest(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'email' => 'bail|required|email|unique:users,email'
			]);

		if( $validator->fails() )
		{
			return $validator;
		}
		else
		{
			$user = Auth::user();
			$user->update([
				'email' => $request->only('email')['email']
				]);
			return 1;
		}
	}

	//JSON
	public function ChangePassword(Request $request)
	{
		if( !Auth::check() ) { return abort(404); }
		$validator = $this->ChangePasswordRequest($request);

		$output = [
		'success' => 0,
		'message' => [],
		'action' => [
		'displayText' => NULL,
		'redirect' => NULL,
		'additional_alert' => NULL
		]
		];

		if( !is_object($validator) )
		{
			if( $validator == 1 )
			{
				$output['success']               = 1;
				$output['action']['displayText'] = trans('messages.change.password_success');
			}
			else
			{
				$output['success']   = 0;
				$output['message'][] = trans('messages.change.password_invalid');
			}
		}
		else
		{
			$errors = [];
			foreach( $validator->errors()->messages() as $attribute => $errs )
			{
				foreach( $errs as $err )
				{
					$errors[] = $err;
				}
			}

			$output['message'] = $errors;
		}

		return json_encode($output);
	}

	public function ChangeEmail(Request $request)
	{
		if( !Auth::check() ) { return abort(404); }
		$validator = $this->ChangeEmailRequest($request);

		$output = [
		'success' => 0,
		'message' => [],
		'action' => [
		'displayText' => NULL,
		'redirect' => NULL,
		'additional_alert' => NULL
		]
		];

		if( !is_object($validator) )
		{
			if( $validator == 1 )
			{
				$output['success']               = 1;
				$output['action']['displayText'] = trans('messages.change.email_success');
			}
			else
			{
				$output['success']   = 0;
				$output['message'][] = trans('messages.change.email_failure');
			}
		}
		else
		{
			$errors = [];
			foreach( $validator->errors()->messages() as $attribute => $errs )
			{
				foreach( $errs as $err )
				{
					$errors[] = $err;
				}
			}

			$output['message'] = $errors;
		}

		return json_encode($output);
	}
}
