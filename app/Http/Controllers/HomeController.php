<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $posts = Post::with('author')
            ->select('posts.*')
            ->join(DB::raw('(SELECT author_id, MAX(created_at) as latest_post FROM posts GROUP BY author_id) as latest_by_author'), function ($join) {
                $join->on('posts.author_id', '=', 'latest_by_author.author_id')
                    ->on('posts.created_at', '=', 'latest_by_author.latest_post');
            })
            ->latest()
            ->paginate(10);

        return view('pages.index', ['posts' => $posts]);
    }

    public function loadMorePosts(Request $request)
    {
        $page = $request->input('page', 1);
        $posts = Post::with('author')
            ->select('posts.*')
            ->join(DB::raw('(SELECT author_id, MAX(created_at) as latest_post FROM posts GROUP BY author_id) as latest_by_author'), function ($join) {
                $join->on('posts.author_id', '=', 'latest_by_author.author_id')
                    ->on('posts.created_at', '=', 'latest_by_author.latest_post');
            })
            ->latest()
            ->paginate(10, ['*'], 'page', $page);

        return response()->json([
            'posts' => view('partials.post-list', ['posts' => $posts])->render(),
            'next_page_url' => $posts->nextPageUrl(),
        ]);
    }
}
