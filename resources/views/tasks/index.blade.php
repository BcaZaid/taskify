<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('To-do') }}
        </h2>
    </x-slot>

    <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
        <form method="POST" action="{{ route('tasks.store') }}">
            @csrf
            <textarea name="message" placeholder="{{ __('Write your task here!') }}"
                class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">{{ old('message') }}</textarea>
            <x-input-error :messages="$errors->get('message')" class="mt-2" />
            <x-primary-button class="mt-4">{{ __('Add') }}</x-primary-button>
        </form>

        <div class="mt-6 bg-white shadow-sm rounded-lg divide-y" x-data="{
            tasks: @entangle('tasks').defer,
            drag: null,
            startDrag(task) {
                this.drag = task;
            },
            dropTask(task) {
                const dragIndex = this.tasks.indexOf(this.drag);
                const dropIndex = this.tasks.indexOf(task);
                this.tasks.splice(dragIndex, 1);
                this.tasks.splice(dropIndex, 0, this.drag);
                // Optional: Call backend to persist the new order
                axios.post('{{ route('tasks.reorder') }}', {
                    tasks: this.tasks.map(t => t.id)
                });
            }
        }">

            @if ($tasks->isEmpty())
                <p class="p-6 text-gray-600 text-center">You have no tasks yet.</p>
            @else
                <div class="task-list">
                    @foreach ($tasks as $task)
                        <div class="p-6 flex space-x-2" x-bind:key="task.id" x-on:dragstart="startDrag(task)"
                            x-on:dragover.prevent x-on:drop="dropTask(task)" draggable="true">
                            <div class="flex-1">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <span class="text-gray-800">{{ $task->user->name }}</span>
                                        <small
                                            class="ml-2 text-sm text-gray-600">{{ $task->created_at->format('j M Y, g:i a') }}
                                        </small>
                                        @unless ($task->created_at->eq($task->updated_at))
                                            <small class="text-sm text-gray-600"> &middot; {{ __('edited') }}</small>
                                        @endunless
                                    </div>
                                    @if ($task->user->is(auth()->user()))
                                        <x-dropdown>
                                            <x-slot name="trigger">
                                                <button>
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="h-4 w-4 text-gray-400" viewBox="0 0 20 20"
                                                        fill="currentColor">
                                                        <path
                                                            d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                                    </svg>
                                                </button>
                                            </x-slot>
                                            <x-slot name="content">
                                                <x-dropdown-link :href="route('tasks.edit', $task)">
                                                    {{ __('Edit') }}
                                                </x-dropdown-link>
                                                <form method="POST" action="{{ route('tasks.destroy', $task) }}">
                                                    @csrf
                                                    @method('delete')
                                                    <x-dropdown-link :href="route('tasks.destroy', $task)"
                                                        onclick="event.preventDefault(); this.closest('form').submit();">
                                                        {{ __('Delete') }}
                                                    </x-dropdown-link>
                                                </form>
                                            </x-slot>
                                        </x-dropdown>
                                    @endif
                                </div>
                                <p class="mt-4 text-lg text-gray-900">{{ $task->message }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
