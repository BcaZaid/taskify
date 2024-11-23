<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="mt-2 mr-2 float-right">
                    <img src="{{ Auth::user()->profile_picture && !str_contains(Auth::user()->profile_picture, 'images/default-profile.png')
                        ? Auth::user()->profile_picture
                        : asset('images/default-profile.png') }}"
                        alt="Profile Picture" class="rounded-full w-32 h-32">
                </div>

                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Welcome, {{ Auth::user()->name }}!</h3>
                    <h4 class="text-md font-semibold text-gray-800 mb-2">Personal Details</h4>
                    <p>Email: {{ Auth::user()->email }}</p>
                </div>
            </div>

            <!-- Display Uploaded Images -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-4">
                <div class="p-6 text-gray-900">
                    <h4 class="text-md font-semibold text-gray-800 mb-4">Your Uploaded Images</h4>
                    <div class="grid grid-cols-3 gap-4">
                        @foreach ($documents as $document)
                            <div> <img src="{{ asset('storage/' . $document->file_path) }}"
                                    class="rounded w-full h-auto" alt="Uploaded Image"> </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
