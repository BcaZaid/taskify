<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Your Tasks') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <form action="{{ route('tasks.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="task" class="block text-gray-700 text-sm font-bold mb-2">New Task:</label>
                        <input type="text" id="task" name="task" required
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Add
                        Task</button>
                </form>
                <!-- Display Tasks -->
                <div class="mt-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Task List</h3>
                    <ul id="task-list">
                        @foreach ($tasks as $task)
                            <li class="flex justify-between items-center mb-2 p-2 border rounded bg-gray-100"
                                data-id="{{ $task->id }}">
                                <span>{{ $task->task }}</span>
                                <div>
                                    <a href="{{ route('tasks.edit', $task->id) }}"
                                        class="text-blue-500 hover:text-blue-700">Edit</a>
                                    <form action="{{ route('tasks.destroy', $task->id) }}" method="POST"
                                        class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-red-500 hover:text-red-700 ml-2">Delete</button>
                                    </form>
                                </div>
                            </li>
                        @endforeach
                    </ul>

                </div>
            </div>
        </div>
    </div>
    <!-- Include Sortable.js -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.3/Sortable.min.js">
        integrity = "sha512-YyVDehBlx1UBi2M4lptzRVE3HECeD5Gf28Dh7hmde3FSKMy57MGoCUWPRCrp2gNqx7xjQ5ANe1Tf4PE8FwXK6Q=="
        crossorigin = "anonymous"
        referrerpolicy = "no-referrer" >
    </script>
    <script>
        // Make task list sortable 
        var el = document.getElementById('task-list');
        var sortable = Sortable.create(el, {
            animation: 150,
            onEnd: function(evt) {
                // Handle task reordering and send updated order to server 
                var order = sortable.toArray();
                // Send order to server 
                fetch('{{ route('tasks.reorder') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        order: order
                    })
                });
            }
        });
    </script>
</x-app-layout>
