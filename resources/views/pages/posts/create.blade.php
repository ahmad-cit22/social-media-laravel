@extends('layouts.master')

@section('content')
    <main class="container max-w-2xl mx-auto space-y-8 mt-8 px-2 min-h-screen">
        <h3 class="text-2xl font-bold leading-9 tracking-tight text-gray-900">
            Create New Post
        </h3>

        @include('partials.create-post-card')

        <!-- Home Button -->
        <div class="flex justify-center mt-6">
            <a href="{{ route('home') }}" class="text-xs rounded-full px-4 py-2 font-semibold bg-gray-600 hover:bg-gray-800 text-white">
                Go to Home
            </a>
        </div>
        <!-- /Home Button -->
    </main>
@endsection

