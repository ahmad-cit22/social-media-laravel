@extends('layouts.master')

@section('content')
    <main class="container max-w-2xl mx-auto space-y-8 mt-8 px-2 min-h-screen">
        <!-- Single post -->
        <section id="newsfeed" class="space-y-6">
            @include('partials.post-card')
        </section>
        <!-- /Single post -->
    </main>
@endsection
