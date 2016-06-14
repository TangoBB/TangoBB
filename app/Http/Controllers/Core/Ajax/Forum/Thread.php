<?php

namespace App\Http\Controllers\Core\Ajax\Forum;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Tango\Database\Post as Post;
use App\User as User;

use Auth;
use Validator;
use Bbcode;

class Thread extends Controller
{
    //
    protected $user, $bbcode;
	public function __construct(User $user, Bbcode $bbcode)
	{
		$this->user = $user;
		$this->bbcode = $bbcode;
	}

    //
	private function ReplyRequest($id, Request $request)
	{
		$validator = Validator::make($request->all(), [
			'title' => 'bail|required',
			'editor' => 'bail|required',
			]);
		if( $validator->fails() )
		{
			return $validator;
		}
		else
		{
			$reply   = Post::findOrFail($id);
			if( $reply['is_locked'] == 1 )
			{
				return 0;
			}

			$title   = $request->only('title')['title'];
			$content = $request->only('editor')['editor'];
			$sid     = Post::insertGetId([
				'post_name' => $title,
				'post_content' => $content,
				'post_slug' => str_slug($title, '_'),
				'post_id' => $id,
				'post_type' => 2,
				'posted_by' => Auth::User()['id']
				]);
			$pTouch  = Post::find($sid);
			$pTouch->touch();

			$tTouch  = Post::find($id);
			$tTouch->touch();

			return $sid;
		}
	}

	private function EditRequest($id, Request $request)
	{
		$validator = Validator::make($request->all(), [
			'editor' => 'bail|required',
			]);
		if( $validator->fails() )
		{
			return $validator;
		}
		else
		{
			$content = $request->only('editor')['editor'];
			Post::where('id', '=', $id)->update(['post_content' => $content]);
			$pTouch  = Post::find($id);
			$pTouch->touch();

			$sid = ($pTouch['post_type'] == 1)? $pTouch['id'] : $pTouch['post_id'];

			return $sid;
		}
	}

	//JSON
	public function Lock($id)
	{
		if( !$this->user->hasPermission(null, 'moderator.lock.post') ) { return abort(404); }

		$output = [
		'success' => 0,
		'message' => [],
		'action' => [
		'displayText' => NULL,
		'redirect' => NULL,
		'result' => 0
		]
		];

		$thread = Post::where([
			['id', '=', $id],
			['post_type', '=', 1]
			])->first();
		if( !empty($thread) )
		{
			if( $thread['is_locked'] == 1 )
			{
				//die('here');
				$update = Post::where('id', $id)->update(['is_locked' => 0]);
				$output['success'] = 1;
			}
			else
			{
				//die('there');
				$update = Post::where('id', $id)->update(['is_locked' => 1]);
				$output['success'] = 1;
				$output['action']['result'] = 1;
			}
		}

		return json_encode($output);
	}

	public function Stick($id)
	{
		if( !$this->user->hasPermission(null, 'moderator.stick.post') ) { return abort(404); }

		$output = [
		'success' => 0,
		'message' => [],
		'action' => [
		'displayText' => NULL,
		'redirect' => NULL,
		'result' => 0
		]
		];

		$thread = Post::where([
			['id', '=', $id],
			['post_type', '=', 1]
			])->first();
		if( !empty($thread) )
		{
			if( $thread['is_stickied'] == 1 )
			{
				//die('here');
				$update = Post::where('id', $id)->update(['is_stickied' => 0]);
				$output['success'] = 1;
			}
			else
			{
				//die('there');
				$update = Post::where('id', $id)->update(['is_stickied' => 1]);
				$output['success'] = 1;
				$output['action']['result'] = 1;
			}
		}

		return json_encode($output);
	}

	public function Delete($id)
	{
		if( !$this->user->hasPermission(null, 'moderator.delete.post') ) { return abort(404); }

		$output = [
		'success' => 0,
		'message' => [],
		'action' => [
		'displayText' => NULL,
		'redirect' => NULL
		]
		];

		$thread = Post::where('id', '=', $id)->first();

		if( !empty($thread) )
		{
			$thread = Post::where('id', '=', $id);
			$thread->first()->Replies()->delete();
			$thread->delete();
        	//Post::where('id', '=', $id)->Replies()->delete();
        	//Post::where('id', '=', $id)->delete();
			$output['success'] = 1;
		}

		return json_encode($output);
	}

	public function Reply($id, Request $request)
	{
		if( !Auth::check() || !Auth::user()->hasPermission(null, 'post.reply') ) { return abort(404); }
        //header("Access-Control-Allow-Origin: *");
        //header('Access-Control-Allow-Credentials: true');
		$output = [
		'success' => 0,
		'message' => [],
		'action' => [
		'displayText' => NULL,
		'redirect' => NULL
		]
		];

		if( $request->isMethod('post') )
		{
            //die(var_dump($request->get('username')));
			$validator = $this->ReplyRequest($id, $request);
			if( !is_object($validator) )
			{
				if( $validator == 0 )
				{
					$output['success']   = 0;
					$output['message'][] = trans('messages.thread.locked');
				}
				else
				{
					$post = Post::where('id', '=', $validator)->first();
					$output['success']               = 1;
					$output['action']['displayText'] = view('requests.post', ['post' => $post])->render();
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
                //die(var_dump($errors));
			}
		}
		return json_encode($output);
	}

	public function Edit($id, Request $request)
	{
		$post = Post::findOrFail($id);
		if( !$this->user->hasPermission(null, 'moderator.delete.post') )
		{
			if(  !Auth::User()->can('update-post', $post) ) {
				return abort(404);
			}
		}

		$output = [
		'success' => 0,
		'message' => [],
		'action' => [
		'displayText' => NULL,
		'redirect' => NULL,
		'additional_alert' => NULL
		]
		];

		if( $request->isMethod('post') )
		{
            //die(var_dump($request->get('username')));
			$validator = $this->EditRequest($id, $request);
			if( !is_object($validator) )
			{
            	//Get updated post.
				$post = Post::where('id', '=', $id)->first();
				$output['success']                    = 1;
				$output['action']['displayText']      = $this->bbcode->renderText($post['post_content']);
				$output['action']['additionalAlert'] = trans('messages.thread.edit_success');
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
                //die(var_dump($errors));
			}
		}
        //die(var_dump(json_encode($output, JSON_UNESCAPED_UNICODE)));
		return json_encode($output, JSON_UNESCAPED_UNICODE);
	}
}
