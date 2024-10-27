@forelse ($posts as $post)
    @include('partials.post-card')
@empty
    <p class="text-center text-gray-500">No posts available.</p>
@endforelse
