@extends('layouts.master')

@section('content')
    <main class="container max-w-xl mx-auto space-y-8 mt-8 px-2 md:px-0 min-h-screen">
        @if (Auth::user())
            <!-- Barta Create Post Card -->
            <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data"
                class="bg-white border-2 border-black rounded-lg shadow mx-auto max-w-none px-4 py-5 sm:px-6 space-y-3">
                @csrf
                <div class="flex items-start">
                    <div class="text-gray-700 font-normal w-full">
                        <textarea
                            class="block w-full p-2 pt-2 text-gray-900 rounded-lg border-none outline-none focus:ring-0 focus:ring-offset-0"
                            name="content" rows="2" placeholder="What's going on, {{ Auth::user()->first_name }}?"></textarea>
                    </div>
                </div>
                @error('content')
                    <div class="text-sm text-red-600">{{ $message }}</div>
                @enderror
                <div class="flex items-center justify-end">
                    <button type="submit"
                        class="text-xs rounded-full px-4 py-2 font-semibold bg-gray-800 hover:bg-black text-white">
                        Post
                    </button>
                </div>
            </form>
        @else
            <p class="text-center text-md text-gray-700">You must <a class="text-indigo-600"
                    href="{{ route('login') }}">login</a> to create a post.</p>
        @endif

        <!-- Newsfeed -->
        <section id="newsfeed" class="space-y-6">
            @if ($search)
                <p class="text-center text-md text-gray-700 flex items-center justify-center">
                    Showing search results for <strong class="ml-1">{{ $search }}</strong>
                    <a href="{{ route('home') }}" class="ml-1 inline text-white text-xs items-center rounded-full p-1 bg-gray-800 hover:bg-black">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </a>
                </p>

                <!-- Searched Users Section -->
                <section class="bg-white border-2 border-gray-300 rounded-lg p-4 space-y-3">
                    <h3 class="text-lg font-semibold text-gray-800">Users</h3>
                    @if ($users->isNotEmpty())
                        <ul class="space-y-2">
                            @foreach ($users as $user)
                                <li class="flex items-center space-x-4">
                                    <a href="{{ route('profile.show', $user->id) }}">
                                        <img src="{{ asset('storage/' . $user->avatar) }}"
                                            alt="{{ $user->fullName }}'s profile picture" class="w-10 h-10 rounded-full">
                                    </a>
                                    <a href="{{ route('profile.show', $user->id) }}">
                                        <div>
                                            <p class="text-gray-800 font-semibold text-underline">{{ $user->fullName }}</p>
                                            <p class="text-sm text-gray-500">
                                                {{ '@' . $user->username . ' . ' . Str::limit($user->email, 20) }}</p>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-center text-gray-500">No users found.</p>
                    @endif
                </section>
            @endif
            @if ($search)
                <h3 class="text-lg font-semibold text-gray-800">Posts</h3>
            @endif
            @include('partials.post-list')
            @if ($posts->hasMorePages())
                <button id="load-more"
                    class="bg-gray-700 hover:bg-gray-900 text-white font-semibold px-4 py-2 rounded-full mx-auto block"
                    data-next-page="{{ route('home.load-more') }}?page={{ $posts->currentPage() + 1 }}">Load More</button>
            @endif
        </section>
    </main>
@endsection

@push('custom-scripts')
    <script>
        document.getElementById('load-more').addEventListener('click', function() {
            const button = this;
            const nextPageUrl = button.getAttribute('data-next-page');
            const searchQuery = document.querySelector('input[name="search"]').value;

            if (nextPageUrl) {
                fetch(`${nextPageUrl}&search=${encodeURIComponent(searchQuery)}`, {
                        headers: {
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.posts) {
                            button.insertAdjacentHTML('beforebegin', data.posts);
                            button.setAttribute('data-next-page', data.next_page_url);
                            if (!data.next_page_url) {
                                const message = document.createElement('p');
                                message.textContent = 'No more posts to load for now!';
                                message.className = 'text-center text-gray-700';
                                button.parentNode.replaceChild(message, button);
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error loading more posts:', error);
                        alert('Could not load more posts. Please try again later.');
                    });
            }
        });
    </script>
@endpush
