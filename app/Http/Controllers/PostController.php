<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use GuzzleHttp\Promise\Create;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function __construct()
    {   
        $this->middleware(['auth', ])->only(['store', 'destroy']);
    }

    public function index()
    {
        $posts = Post::with(['user', 'likes'])->paginate(20); // Collection

        return view('posts.index', [
            'posts' => $posts
        ]);
    }

    public function show(Post $post) 
    {
        return view('posts.show', [
            'post' => $post
        ]);
    }

    public function store(Request $request) 
    {
        // validation
        $this->validate($request, [
            'body' => 'required',
        ]);

        $request->user()->posts()->Create($request->only('body'));

        return back();
    }

    public function destroy(Post $post) 
    {
        $this->authorize('delete', $post);

        $post->delete();

        return back();
    }
}
