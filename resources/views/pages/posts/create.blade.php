@extends('layouts.master')

@section('content')
    <main class="container max-w-2xl mx-auto space-y-8 mt-8 px-2 min-h-screen">
        <h3 class="text-2xl font-bold leading-9 tracking-tight text-gray-900">
            Create New Post
        </h3>
        <!-- Barta create Post Card -->
        <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data"
            class="bg-white border-2 border-black rounded-lg shadow mx-auto max-w-none px-4 py-5 sm:px-6 space-y-3">
            @csrf
            <!-- create Post Card Top -->
            <div>
                <div class="flex items-start /space-x-3/">
                    <!-- Content -->
                    <div class="text-gray-700 font-normal w-full">
                        <textarea
                            class="block w-full p-2 pt-2 text-gray-900 rounded-lg border-none outline-none focus:ring-0 focus:ring-offset-0"
                            name="content" rows="2" placeholder="What's going on, {{ Auth::user()->first_name }}?"></textarea>
                    </div>
                </div>
            </div>
            @error('content')
                <div class="text-sm text-red-600">
                    {{ $message }}
                </div>
            @enderror

            <!-- create Post Card Bottom -->

            <div>
                <!-- Card Bottom Action Buttons -->
                <div class="flex items-center justify-end">

                    <div>
                        <!-- Post Button -->
                        <button type="submit"
                            class="-m-2 flex gap-2 text-xs items-center rounded-full px-4 py-2 font-semibold bg-gray-800 hover:bg-black text-white">
                            Post
                        </button>
                        <!-- /Post Button -->
                    </div>
                </div>
                <!-- /Card Bottom Action Buttons -->
            </div>
            <!-- /create Post Card Bottom -->
        </form>
        <!-- /Barta create Post Card -->

        <!-- Home Button -->
        <div class="flex justify-center mt-6">
            <a href="{{ route('home') }}" class="text-xs rounded-full px-4 py-2 font-semibold bg-gray-600 hover:bg-gray-800 text-white">
                Go to Home
            </a>
        </div>
        <!-- /Home Button -->
    </main>
@endsection

