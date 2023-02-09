<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\PostDeetailResource;

class PostContoller extends Controller
{
    public function index()
    {
        $posts = Post::all();
        return PostDeetailResource::collection($posts->loadMissing(['writter:id,username', 'comments:id,post_id,user_id']));
    }

    public function show($id)
    {
        $post = Post::with('writter:id,username')->findOrFail($id);
        // return response()->json(['data' => $post]);
        return new PostDeetailResource($post->loadMissing(['writter:id,username', 'comments:id,post_id,user_id']));
    }

    public function show2($id)
    {
        $post = Post::findOrFail($id);
        // return response()->json(['data' => $post]);
        return new PostDeetailResource($post);
    }

    public function me(Request $request)
    {
        $user = Auth::user();
        return response()->json(['data' => $user]);
    }

    public function store(Request $request)
    {

        $validate = $request->validate([
            'title' => 'required|max:255',
            'news_content' => 'required'
        ]);

        $request['author'] = Auth::user()->id;
        $post = Post::create($request->all());

        return new PostDeetailResource($post->loadMissing('writter:id,username'));
    }

    public function update(Request $request, $id)
    {
        $validate = $request->validate([
            'title' => 'required|max:255',
            'news_content' => 'required'
        ]);

        $post = Post::findOrFail($id);
        $post->update($request->all());

        return new PostDeetailResource($post->loadMissing('writter:id,username'));
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();
        return new PostDeetailResource($post->loadMissing('writter:id,username'));
    }
}
