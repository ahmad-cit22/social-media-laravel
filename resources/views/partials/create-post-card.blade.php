<!-- Barta create Post Card -->
<form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data"
    class="bg-white border-2 border-black rounded-lg shadow mx-auto max-w-none px-4 py-5 sm:px-6 space-y-3">
    @csrf
    <!-- create Post Card Top -->
    <div>
        <div class="flex items-start /space-x-3/">
            <!-- User Avatar -->
            <div class="flex-shrink-0">
                <img class="h-10 w-10 rounded-full object-cover"
                    src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('storage/avatars/def-avatar.jpg') }}"
                    alt="image-{{ Auth::user()->fullName }}" />
            </div>
            <!-- /User Avatar -->

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
        <div class="flex items-center justify-between">

            <div class="flex gap-4 text-gray-600 items-center justify-between">
                <!-- Upload Picture Button -->
                <div>
                    <input type="file" name="picture" id="picture" class="hidden" accept="image/*" />
                    <label for="picture"
                        class="-m-2 flex gap-2 text-xs items-center rounded-full p-2 text-gray-600 hover:text-gray-800 cursor-pointer">
                        <span class="sr-only">Picture</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                        </svg>
                    </label>
                </div>
                <!-- /Upload Picture Button -->

                <!-- Image Preview -->
                <div id="image-preview-container" class="relative" style="display: none">
                    <img id="image-preview" class="h-16 w-16 rounded-lg object-cover" alt="Preview" />
                    <button type="button" id="remove-image"
                        class="absolute top-[-1px] right-0 px-1 bg-black bg-opacity-25 text-white rounded-full"
                        style="line-height: normal !important">
                        &times;
                    </button>
                </div>
                <!-- Image Preview -->
            </div>

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

@push('custom-scripts')
    <script>
        const pictureInput = document.getElementById('picture');
        const imagePreviewContainer = document.getElementById('image-preview-container');
        const imagePreview = document.getElementById('image-preview');
        const removeImageButton = document.getElementById('remove-image');

        pictureInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreviewContainer.style.display = 'block';
                }
                reader.readAsDataURL(file);
            }
        });

        // Remove image preview
        removeImageButton.addEventListener('click', function() {
            pictureInput.value = '';
            imagePreview.src = '';
            imagePreviewContainer.style.display = 'none';
        });
    </script>
@endpush
