<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Requests\PostRequest;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function show(Post $post)
    {
        $post->increment('views_count');

        return view('posts.show', compact('post'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(PostRequest $request)
    {
        Post::create([
            'author_id' => Auth::id(),
            'content' => $request->content,
        ]);

        return redirect()->route('posts.index')->with('success', 'Post created successfully.');
    }

    public function edit(Post $post)
    {
        if (Auth::id() !== $post->author_id) {
            return redirect()->route('posts.index')->with('error', 'Unauthorized action.');
        }

        return view('posts.edit', compact('post'));
    }

    public function update(PostRequest $request, Post $post)
    {
        if (Auth::id() !== $post->author_id) {
            return redirect()->route('posts.index')->with('error', 'Unauthorized action.');
        }

        $post->update($request->validated());

        return redirect()->route('posts.show', $post)->with('success', 'Post updated successfully.');
    }

    public function destroy(Post $post)
    {
        if (Auth::id() !== $post->author_id) {
            return redirect()->route('posts.index')->with('error', 'Unauthorized action.');
        }

        $post->delete();

        return redirect()->route('posts.index')->with('success', 'Post deleted successfully.');
    }
}
