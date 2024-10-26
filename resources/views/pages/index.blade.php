@extends('layouts.master')

@section('content')
    <main class="container max-w-xl mx-auto space-y-8 mt-8 px-2 md:px-0 min-h-screen">
        @if (Auth::user())
            <!-- Barta Create Post Card -->
            <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data"
                class="bg-white border-2 border-black rounded-lg shadow mx-auto max-w-none px-4 py-5 sm:px-6 space-y-3">
                @csrf
                <!-- Create Post Card Top -->
                <div>
                    <div class="flex items-start">

                        <!-- Content -->
                        <div class="text-gray-700 font-normal w-full">
                            <textarea
                                class="block w-full p-2 pt-2 text-gray-900 rounded-lg border-none outline-none focus:ring-0 focus:ring-offset-0"
                                name="content" rows="2"
                                placeholder="What's going on, {{ Auth::user()->first_name }}? (Write within 250 characters)"></textarea>
                        </div>
                    </div>
                </div>
                @error('content')
                    <div class="text-sm text-red-600">
                        {{ $message }}
                    </div>
                @enderror

                <!-- Create Post Card Bottom -->
                <div class="flex items-center justify-end">
                    <button type="submit"
                        class="-m-2 flex gap-2 text-xs items-center rounded-full px-4 py-2 font-semibold bg-gray-800 hover:bg-black text-white">
                        Post
                    </button>
                </div>
            </form>
        @else
            <p class="text-center text-md text-gray-700">You must <a class="text-indigo-600"
                    href="{{ route('login') }}">login</a> to create a post.
            </p>
        @endif

        <!-- Newsfeed -->
        <section id="newsfeed" class="space-y-6">
            @forelse ($posts as $post)
                @include('partials.post-card', ['post' => $post])
            @empty
                <div class="text-center p-12 border border-gray-800 rounded-xl">
                    <h1 class="text-3xl justify-center items-center">Welcome to Barta!</h1>
                </div>
                <p class="text-center text-md font-semibold">No Posts Found!</p>
            @endforelse
            {{-- @if ($posts->hasMorePages())
                <button id="load-more"
                    class="bg-gray-700 hover:bg-gray-900 text-white font-semibold px-4 py-2 rounded-full mx-auto block"
                    data-next-page="{{ $posts->nextPageUrl() }}">Load More</button>
            @endif --}}
        </section>
    </main>
@endsection

@push('custom-scripts')
@endpush
