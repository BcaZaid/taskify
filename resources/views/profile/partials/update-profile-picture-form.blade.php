<div class="mt-4">
    @if (session('profile_success'))
        <div class="bg-green-500 text-white p-4 rounded mb-4"> {{ session('profile_success') }} </div>
    @endif
    <form action="{{ route('profile.update.picture') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="profile_picture" class="block text-gray-700 text-sm font-bold mb-2">Profile Picture:</label>
            <input type="file" id="profile_picture" name="profile_picture"
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>

        <button type="submit"
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Update
            Profile Picture</button>
    </form>

    @if (auth()->user()->profile_picture)
        <div class="mt-4">
            <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}" alt="Profile Picture"
                class="rounded-full w-32 h-32">
        </div>
    @endif
</div>
