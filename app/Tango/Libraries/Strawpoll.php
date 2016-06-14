<?php

namespace App\Tango\Libraries;

use GuzzleHttp\Client;

use Auth;

class Strawpoll {

	protected $apiUrl  = 'https://strawpoll.me/api/v2/polls';

	protected $default = [
		'title',
		'options' => [],
		'multi' => false
	];

}
