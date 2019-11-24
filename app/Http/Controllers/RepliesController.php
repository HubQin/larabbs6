<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Reply;
use App\Http\Requests\ReplyRequest;

class RepliesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @param ReplyRequest $request
     * @param Reply $reply
     * @return \Illuminate\Http\RedirectResponse
     */
	public function store(ReplyRequest $request, Reply $reply)
	{
        $reply->content = $request->content;
        $reply->user_id = Auth::id();
        $reply->topic_id = $request->topic_id;
        $reply->save();
		return redirect()->to($reply->topic->link(['#reply' . $reply->id]))->with('success', '评论创建成功');
	}

    /**
     * @param Reply $reply
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
	public function destroy(Reply $reply)
	{
		$this->authorize('destroy', $reply);
		$reply->delete();

        return redirect()->to($reply->topic->link())->with('success', '评论删除成功！');
	}
}
