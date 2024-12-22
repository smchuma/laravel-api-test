<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller implements HasMiddleware
{
    /**
     * Display a listing of the resource.

     */

     public static function middleware() {

        return [
            new Middleware('auth:sanctum', except: ['index', 'show'])
        ];
     }


    public function index()
    {
        //
        return Post::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $fields = $request->validate([
            "title"=> "required|max:255",
            "body" => "required",
        ]);

        $post = $request->user()->posts()->create($fields);

        return ['message' => 'Post created successfully', 'post' => $post];

    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
        try {
            $post = Post::findOrFail($id);
            return ['post'=> $post];

        } catch (ModelNotFoundException $e) {
            return response()->json(['message'=> 'Post Not Found'],404);
        }

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        //

        Gate::authorize('modify', $post);


        $fields = $request->validate([
            "title"=> "required|max:255",
            "body" => "required",
        ]);

        $post->update($fields);

        return ['message' => 'Post updated successfully', 'post' => $post];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //
        Gate::authorize('modify', $post);

        $post->delete();
        return ['message'=> 'Post deleted successfully'];

    }
}
