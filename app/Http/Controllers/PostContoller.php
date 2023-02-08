<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostContoller extends Controller
{
    public function index()
    {
        $posts = Post::all();

        return response()->json($posts);
    }
}
