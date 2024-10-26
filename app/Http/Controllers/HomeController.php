<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search', '');

        $users = [];
        if ($search) {
            $users = User::where('username', 'like', "%{$search}%")
                ->orWhere('first_name', 'like', "%{$search}%")
                ->orWhere('last_name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->get();
        }

        $posts = Post::with('author')
            ->select('posts.*')
            ->when($search, function ($query) use ($search) {
                $query->whereHas('author', function ($query) use ($search) {
                    $query->where('username', 'like', "%{$search}%")
                        ->orWhere('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            }, function ($query) {
                $query->join(DB::raw('(SELECT author_id, MAX(created_at) as latest_post FROM posts GROUP BY author_id) as latest_by_author'), function ($join) {
                    $join->on('posts.author_id', '=', 'latest_by_author.author_id')
                        ->on('posts.created_at', '=', 'latest_by_author.latest_post');
                });
                $query->orderByRaw('RAND()');
            })
            ->latest()
            ->paginate(10);

        return view('pages.index', ['posts' => $posts, 'users' => $users, 'search' => $search]);
    }

    public function loadMorePosts(Request $request)
    {
        $page = $request->input('page', 1);
        $search = $request->input('search', '');

        $posts = Post::with('author')
            ->select('posts.*')
            ->when($search, function ($query) use ($search) {
                $query->whereHas('author', function ($query) use ($search) {
                    $query->where('username', 'like', "%{$search}%")
                        ->orWhere('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            }, function ($query) {
                $query->join(DB::raw('(SELECT author_id, MAX(created_at) as latest_post FROM posts GROUP BY author_id) as latest_by_author'), function ($join) {
                    $join->on('posts.author_id', '=', 'latest_by_author.author_id')
                        ->on('posts.created_at', '=', 'latest_by_author.latest_post');
                });
                $query->orderByRaw('RAND()');
            })
            ->latest()
            ->paginate(10, ['*'], 'page', $page);

        return response()->json([
            'posts' => view('partials.post-list', ['posts' => $posts])->render(),
            'next_page_url' => $posts->nextPageUrl(),
        ]);
    }
}
