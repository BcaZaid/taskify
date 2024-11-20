<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                @if (Auth::user()->profile_picture)
                    <div class="mt-2 float-right">
                        <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}" alt="Profile Picture"
                            class="rounded-full w-32 h-32">
                    </div>
                @else
                    <div class="mt-2 rem float-right">
                        <img src="{{ asset('default-profile.png') }}" alt="Default Profile Picture"
                            class="rounded-full w-32 h-32">
                    </div>
                @endif
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Welcome, {{ Auth::user()->name }}!</h3>

                    <h4 class="text-md font-semibold text-gray-800 mb-2">Personal Details</h4>
                    <p>Email: {{ Auth::user()->email }}</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
