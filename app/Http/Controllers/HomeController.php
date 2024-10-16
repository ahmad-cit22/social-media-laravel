<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $user = User::where('id', session('user_id'))->first();
        // $posts = Post::with('author')->latest()->get();
        $latestPosts = Post::select('posts.*')
            ->join(DB::raw('(SELECT MAX(id) as latest_post_id FROM posts GROUP BY author_id) as latest_posts'), function ($join) {
                $join->on('posts.id', '=', 'latest_posts.latest_post_id');
            })
            ->with('author') // Eager load the author relationship
            ->latest() // Order by most recent post
            ->get();

        // Get the remaining posts (excluding the latest ones)
        $remainingPosts = Post::select('posts.*')
            ->whereNotIn('posts.id', $latestPosts->pluck('id')->toArray()) // Exclude the latest posts
            ->join(DB::raw('(SELECT MAX(id) as post_id FROM posts WHERE id NOT IN (' . implode(',', $latestPosts->pluck('id')->toArray()) . ') GROUP BY author_id) as remaining_posts'), function ($join) {
                $join->on('posts.id', '=', 'remaining_posts.post_id');
            })
            ->with('author')
            ->latest()
            ->get();

        // Merge the collections (latest posts first, then the remaining posts)
        $allPosts = $latestPosts->merge($remainingPosts);

        return view('pages.index', ['user' => $user, 'posts' => $allPosts]);
    }
}
