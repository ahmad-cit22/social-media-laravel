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
        $user = User::where('id', session('user_id'))->first();

        return view('pages.posts.show', compact('post', 'user'));
    }

    public function create()
    {
        //
    }

    public function store(PostRequest $request)
    {
        $allowed_tags = '<b><i><p><strong><em><ul><ol><li><a>';
        $content = $request->content ? strip_tags($request->content, $allowed_tags) : null;

        if (!$content) {
            return redirect()->route('home')->with('error', 'Invalid content! Please write your post properly.');
        }

        Post::create([
            'author_id' => session('user_id'),
            'content' => $content,
        ]);

        return redirect()->route('home')->with('success', 'Post created successfully.');
    }

    public function edit(Post $post)
    {
        if (!session('user_id') || session('user_id') !== $post->author_id) {
            return redirect()->route('home')->with('error', 'You can not edit post of others.');
        }

        $user = User::where('id', session('user_id'))->first();

        return view('pages.posts.edit', compact('post', 'user'));
    }

    public function update(PostRequest $request, Post $post)
    {
        if (!session('user_id') || session('user_id') !== $post->author_id) {
            return redirect()->route('posts.index')->with('error', 'You can not update post of others.');
        }

        $allowed_tags = '<b><i><p><strong><em><ul><ol><li><a>';
        $content = $request->content ? strip_tags($request->content, $allowed_tags) : null;

        if (!$content) {
            return redirect()->route('home')->with('error', 'Invalid content! Please write your post properly.');
        }

        $post->update([
            'content' => $content,
        ]);

        return redirect()->route('posts.show', $post)->with('success', 'Post updated successfully.');
    }

    public function destroy(Post $post)
    {
        if (!session('user_id') || session('user_id') !== $post->author_id) {
            return redirect()->route('home')->with('error', 'You can not delete post of others.');
        }

        $post->delete();

        return redirect()->route('home')->with('success', 'Post deleted successfully.');
    }
}
