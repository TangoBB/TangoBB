<?php

namespace App\Http\Controllers\Core\Ajax\Forum;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Tango\Database\Category as Cate;
use App\Tango\Database\Post as Post;
use App\User as User;

use Auth;
use Validator;

class Category extends Controller
{
    //Private Functions
    private function PostRequest($id, Request $request)
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
                'category_id' => $id,
                'post_type' => 1,
                'posted_by' => Auth::User()['id']
                ]);
            $pTouch  = Post::find($sid);
            $pTouch->touch();

            return $sid;
        }
    }

    //JSON
    public function Post($id, Request $request)
    {
        if( !Auth::check() || !Auth::user()->hasPermission(null, 'post.create') ) { return abort(404); }
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
            $validator = $this->PostRequest($id, $request);
            if( !is_object($validator) )
            {
                $thread = Post::where('id', '=', $validator)->first();
                $output['success']               = 1;
                $output['action']['redirect']    = route('Forum::Thread::Thread', ['slug' => $thread['post_slug'], 'id' => $thread['id']]);
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
