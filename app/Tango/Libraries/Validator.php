<?php

namespace App\Tango\Libraries;

use App\User as User;

use Hash;


class Validator {

	public function AuthCheck($attribute, $value, $parameters, $validator)
	{
		//die(var_dump($validator->getData()['username']));
		$data = $validator->getData();
		$user = User::where('name', '=', $data['username'])->first();

		if( !empty($user) )
		{
			return Hash::check($data['password'], $user->password);
		}

		return false;
	}

	public function AuthPermission($attribute, $value, $parameters, $validator)
	{
		$data = $validator->getData();
		$user = User::where('name', '=', $data['username'])->first();
		return $user->hasPermission($user, 'account.login');
	}

}
