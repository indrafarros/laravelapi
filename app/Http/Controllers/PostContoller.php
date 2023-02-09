<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\PostDeetailResource;
use Illuminate\Support\Facades\Storage;

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
        if ($request->file) {
            $ext = $request->file->extension();
            $fileName = $this->generateRandomString();

            Storage::putFileAs('image', $request->file, $fileName . '.' . $ext);
            $request['image'] = $fileName . '.' . $ext;
        }
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

    function generateRandomString($length = 30)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
