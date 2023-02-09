<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'post_id' => 'required|exists:posts,id',
            'comments_content' => 'required'
        ]);
        $request['user_id'] = Auth::user()->id;
        $comment = Comment::create($request->all());

        return new CommentResource($comment->loadMissing(['commentator:id,username']));
        // return response()->json($comment->loadMissing(['commentator']));
    }

    public function update(Request $request, $id)
    {

        $validate = $request->validate([
            'comments_content' => 'required'
        ]);

        $comment = Comment::findOrFail($id);
        // $comment = Comment::update($request->only('comments_content'));
        $comment->update($request->only('comments_content'));

        return new CommentResource($comment->loadMissing(['commentator:id,username']));
    }

    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();
        return new CommentResource($comment->loadMissing('commentator:id,username'));
    }
}
