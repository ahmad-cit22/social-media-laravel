<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        if (!session()->has('user_id')) {
            return redirect()->route('login')->with('error', 'Please login to access news feed.');
        }

        $user = DB::table('users')->where('id', session('user_id'))->first();

        return view('pages.index', ['user' => $user]);
    }
}
