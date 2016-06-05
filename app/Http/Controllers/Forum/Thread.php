<?php

namespace App\Http\Controllers\Forum;

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

	public function Index($slug, $id, Request $request)
	{
		if( !$this->user->hasPermission(null, 'post.view') ) { return abort(404); }

		if( $request->isMethod('post') )
		{
			$validator = $this->ReplyRequest($id, $request);
			if( !is_object($validator) )
			{
				if( $validator == 0 )
				{
					return redirect()->route('Forum::Thread::Thread', ['slug' => $slug, 'id' => $id])->with('success', trans('messages.thread.locked'));
				}
				else
				{
					return redirect()->route('Forum::Thread::Thread', ['slug' => $slug, 'id' => $id])->with('fail', trans('messages.thread.edit_success'));
				}
			}
			else
			{
				return redirect()->route('Forum::Thread::Thread', ['slug' => $slug, 'id' => $id])->withErrors($validator);
			}
		}

		$thread = Post::where([
			['id', '=', $id],
			['post_slug', '=', $slug],
			['post_type', '=', 1]
			])->first();
		if( !empty($thread) )
		{
			$replies = $thread->Replies()->where('post_type', 2)->orderBy('created_at', 'asc')->paginate(11);
			return view('forum.thread', ['thread' => $thread, 'replies' => $replies]);
		}
		else
		{
			return abort(404);
		}
	}

	//Not in use. Check Index().
	public function Reply($id, Request $request)
	{
		if( !Auth::check() || !Auth::user()->hasPermission(null, 'post.reply') ) { return abort(404); }
		$post = Post::findOrfail($id);

		if( $request->isMethod('post') )
		{
			$validator = $this->ReplyRequest($id, $request);
			if( !is_object($validator) )
			{
				if( $validator == 0 )
				{
					return redirect()->route('Forum::Thread::Thread', ['slug' => $post['post_slug'], 'id' => $post['id']])->with('error', trans('messages.thread.locked'));
				}
				else
				{
					return redirect()->route('Forum::Thread::Thread', ['slug' => $post['post_slug'], 'id' => $post['id']])->with('success', trans('messages.thread.edit_success'));
				}
			}
			else
			{
				return redirect()->route('Forum::Thread::Thread', ['slug' => $post['post_slug'], 'id' => $post['id']])->withErrors($validator);
			}
		}

		return view('forum.replyPost', ['post' => $post]);
	}

	public function Edit($id, Request $request)
	{
		$post = Post::findOrFail($id);
		if( Auth::User()->cannot('update-post', $post) || !$this->user->hasPermission(null, 'moderator.delete.post') ) { return abort(404); }

		if( $request->isMethod('post') )
		{
			if( $post['post_type'] == 1 )
			{
				$slug = $post['post_slug'];
				$id   = $post['id'];
			}
			else
			{
				$parent = Post::findorfail($post['post_id']);
				$slug   = $parent['post_slug'];
				$id     = $parent['id'];
			}

			$validator = $this->EditRequest($id, $request);
			if( !is_object($validator) )
			{
				return redirect()->route('Forum::Thread::Thread', ['slug' => $slug, 'id' => $id])->with('success', trans('messages.thread.edit_success'));
			}
			else
			{
				return redirect()->route('Forum::Thread::Thread', ['slug' => $slug, 'id' => $id])->withErrors($validator);
			}
		}

		return view('forum.editPost', ['post' => $post]);
	}

	public function Delete($id)
	{
		if( !$this->user->hasPermission(null, 'moderator.delete.post') ) { return abort(404); }

		$thread = Post::findorfail($id);
		$thread->Replies()->delete();
		$thread->delete();

		return redirect()->route('Index::Index')->with('success', trans('messages.thread.delete_success'));
	}

	//JSON
	public function JsonDelete($id)
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

	public function JsonReply($id, Request $request)
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

	public function JsonEdit($id, Request $request)
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
