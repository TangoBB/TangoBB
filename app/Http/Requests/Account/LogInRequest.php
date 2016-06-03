<?php

namespace App\Http\Requests\Account;

use App\Http\Requests\Request;

class LogInRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => 'bail|required',
            'password' => 'required'
        ];
    }
}
