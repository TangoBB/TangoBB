<?php

namespace App\Http\Controllers\Forum;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Tango\Database\Post as Post;
use App\User as User;

use Auth;
use Validator;

class Thread extends Controller
{
	protected $user;
	public function __construct(User $user)
	{
		$this->user = $user;
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
			$title   = $request->only('title')['title'];
			$content = htmlspecialchars($request->only('editor')['editor'], ENT_NOQUOTES);
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

	public function Index($slug, $id, Request $request)
	{
		if( !$this->user->hasPermission(null, 'post.view') ) { return abort(404); }

		if( $request->isMethod('post') )
		{
			$validator = $this->ReplyRequest($id, $request);
			if( !is_object($validator) )
			{
				return redirect()->route('Forum::Thread::Thread', ['slug' => $slug, 'id' => $id])->with('success', trans('messages.thread.reply_success'));
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

	//JSON
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
                $post = Post::where('id', '=', $validator)->first();
                $output['success']               = 1;
                $output['action']['displayText'] = view('requests.post', ['post' => $post])->render();
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
}
