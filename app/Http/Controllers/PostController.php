<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Requests\PostRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function show(Post $post)
    {
        $post->increment('views_count');
        $user = Auth::user();

        return view('pages.posts.show', compact('post', 'user'));
    }

    public function create()
    {
        //
    }

    public function store(PostRequest $request)
    {
        $allowed_tags = '<b><i><p><strong><em><ul><ol><li><a>';
        $content = strip_tags($request->content, $allowed_tags);

        if (empty(trim($content))) {
            return redirect()->route('home')->with('error', 'Invalid content! Please write your post properly.');
        }

        Post::create([
            'author_id' => Auth::id(),
            'content' => $content,
        ]);

        return redirect()->route('home')->with('success', 'Post created successfully.');
    }

    public function edit(Post $post)
    {
        if (!$this->isAuthorized($post)) {
            return redirect()->route('home')->with('error', 'Sorry! You are not authorized for this action.');
        }

        $user = Auth::user();

        return view('pages.posts.edit', compact('post', 'user'));
    }

    public function update(PostRequest $request, Post $post)
    {
        if (!$this->isAuthorized($post)) {
            return redirect()->route('home')->with('error', 'Sorry! You are not authorized for this action.');
        }

        $allowed_tags = '<b><i><p><strong><em><ul><ol><li><a>';
        $content = strip_tags($request->content, $allowed_tags);

        if (empty(trim($content))) {
            return redirect()->route('home')->with('error', 'Invalid content! Please write your post properly.');
        }

        $post->update([
            'content' => $content,
        ]);

        return redirect()->route('posts.show', $post)->with('success', 'Post updated successfully.');
    }

    public function destroy(Post $post)
    {
        if (!$this->isAuthorized($post)) {
            return redirect()->route('home')->with('error', 'Sorry! You are not authorized for this action.');
        }

        $post->delete();

        return redirect()->route('home')->with('success', 'Post deleted successfully.');
    }

    protected function isAuthorized(Post $post)
    {
        if (!Auth::check() || Auth::id() !== $post->author_id) {
            return false;
        }

        return true;
    }
}
