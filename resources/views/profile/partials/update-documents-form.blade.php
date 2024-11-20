<div class="max-w-xl">
    @if (session('success'))
        <div class="bg-green-500 text-white p-4 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif
    <form action="{{ route('profile.updateDocuments') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-4">
            <label for="documents" class="block text-gray-700 text-sm font-bold mb-2">Upload Documents:</label>
            <input type="file" id="documents" name="documents[]" multiple
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>
        <button type="submit"
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Upload
            Documents</button>
    </form>
</div>
