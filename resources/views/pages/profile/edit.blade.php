@extends('layouts.master')

@section('content')
    <main class="container max-w-xl mx-auto space-y-8 mt-8 px-2 md:px-0 min-h-screen">
        <!-- Profile Edit Form -->
        <form action="{{ route('profile.update', $user->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="space-y-12">
                <div class="border-b border-gray-900/10 pb-12">
                    <h2 class="text-xl font-semibold leading-7 text-gray-900">
                        Edit Profile
                    </h2>
                    <p class="mt-1 text-sm leading-6 text-gray-600">
                        This information will be displayed publicly so be careful what you
                        share.
                    </p>

                    <div class="mt-10 border-b border-gray-900/10 pb-12">
                        <div class="col-span-full mt-10 pb-10">
                            <label for="avatar" class="block text-sm font-medium leading-6 text-gray-900">Photo</label>
                            <div class="mt-2 flex items-center gap-x-3">
                                <input class="hidden" type="file" name="avatar" id="avatar"
                                    onchange="readURL(this);" />
                                <img id="avatar-preview" class="h-32 w-32 rounded-full"
                                    src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('storage/avatars/def-avatar.jpg') }}"
                                    alt="{{ $user->fullName }}" />
                                <label for="avatar">
                                    <div
                                        class="rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 hover:cursor-pointer">
                                        Change
                                    </div>
                                </label>
                                @error('avatar')
                                    <div class="text-sm text-red-600">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                            <div class="sm:col-span-3">
                                <label for="first_name" class="block text-sm font-medium leading-6 text-gray-900">First
                                    name</label>
                                <div class="mt-2">
                                    <input type="text" name="first_name" id="first_name" autocomplete="first_name"
                                        placeholder="Your first name" value="{{ $user->first_name }}"
                                        class="block w-full rounded-md border-0 p-2 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-gray-600 sm:text-sm sm:leading-6" />
                                </div>
                                @error('first_name')
                                    <div class="text-sm text-red-600">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="sm:col-span-3">
                                <label for="last_name" class="block text-sm font-medium leading-6 text-gray-900">Last
                                    name</label>
                                <div class="mt-2">
                                    <input type="text" name="last_name" id="last_name" value="{{ $user->last_name }}"
                                        placeholder="Your last name" autocomplete="last_name"
                                        class="block w-full rounded-md border-0 p-2 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-gray-600 sm:text-sm sm:leading-6" />
                                </div>
                                @error('last_name')
                                    <div class="text-sm text-red-600">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="sm:col-span-3">
                                <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email
                                    address</label>
                                <div class="mt-2">
                                    <input id="email" name="email" type="email" autocomplete="email"
                                        value="{{ $user->email }}" placeholder="Your email address"
                                        class="block w-full rounded-md border-0 p-2 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-gray-600 sm:text-sm sm:leading-6" />
                                </div>
                                @error('email')
                                    <div class="text-sm text-red-600">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="sm:col-span-3">
                                <label for="username"
                                    class="block text-sm font-medium leading-6 text-gray-900">Username</label>
                                <div class="mt-2">
                                    <input type="text" name="username" id="username" value="{{ $user->username }}"
                                        placeholder="Your username" autocomplete="username"
                                        class="block w-full rounded-md border-0 p-2 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-gray-600 sm:text-sm sm:leading-6" />
                                </div>
                                @error('username')
                                    <div class="text-sm text-red-600">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-span-full">
                                <label for="bio" class="block text-sm font-medium leading-6 text-gray-900">Bio</label>
                                <div class="mt-2">
                                    <textarea id="bio" name="bio" rows="3"
                                        class="block w-full rounded-md border-0 p-2 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-gray-600 sm:text-sm sm:leading-6">
                                            {{ $user->bio }}
                                        </textarea>
                                </div>
                                @error('bio')
                                    <div class="text-sm text-red-600">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <p class="mt-3 text-sm leading-6 text-gray-600">
                                    Write a few sentences about yourself.
                                </p>
                            </div>

                            <div class="col-span-full">
                                <label for="password" class="block text-sm font-medium leading-6 text-gray-900">Current
                                    Password</label>
                                <div class="mt-2">
                                    <input type="password" name="password" id="password" autocomplete="password"
                                        placeholder="••••••••"
                                        class="block w-full rounded-md border-0 p-2 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-gray-600 sm:text-sm sm:leading-6" />
                                </div>
                                @error('password')
                                    <div class="text-sm text-red-600">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-span-full">
                                <label for="new_password" class="block text-sm font-medium leading-6 text-gray-900">New
                                    Password</label>
                                <div class="mt-2">
                                    <input type="password" name="new_password" id="new_password"
                                        autocomplete="new_password" placeholder="••••••••"
                                        class="block w-full rounded-md border-0 p-2 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-gray-600 sm:text-sm sm:leading-6" />
                                </div>
                                @error('new_password')
                                    <div class="text-sm text-red-600">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-span-full">
                                <label for="new_password_confirmation"
                                    class="block text-sm font-medium leading-6 text-gray-900">Confirm New
                                    Password</label>
                                <div class="mt-2">
                                    <input type="password" name="new_password_confirmation"
                                        id="new_password_confirmation" autocomplete="new_password_confirmation"
                                        placeholder="••••••••"
                                        class="block w-full rounded-md border-0 p-2 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-gray-600 sm:text-sm sm:leading-6" />
                                </div>
                                @error('new_password_confirmation')
                                    <div class="text-sm text-red-600">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-x-6">
                <a href="{{ route('profile.show', $user->id) }}" class="text-sm font-semibold leading-6 text-gray-900">
                    Cancel
                </a>
                <button type="submit"
                    class="rounded-md bg-gray-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-gray-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-600">
                    Save
                </button>
            </div>
        </form>
        <!-- /Profile Edit Form -->
    </main>
@endsection

@push('custom-scripts')
    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('avatar-preview').src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endpush
