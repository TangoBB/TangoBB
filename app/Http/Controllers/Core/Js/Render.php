<?php

namespace App\Http\Controllers\Core\Js;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Bbcode;

class Render extends Controller
{
    //
    protected $bbcode;

    public function __construct(Bbcode $bbcode)
    {
    	$this->bbcode = $bbcode;
    }

    public function Render()
    {
    	$editor_buttons = $this->bbcode->editorButtons();

    	return response()->view('js.master', ['editor_buttons' => $editor_buttons])->header('Content-Type', 'Text/Javascript');

    	//return view('js/master', ['editor_buttons' => $editor_buttons]);
    }
}
