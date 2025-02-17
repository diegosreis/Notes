<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Notebooks
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <x-link-button href="{{route('notebooks.create')}}"
                           class="bg-indigo-500 hover:bg-indigo-600 text-white font-bold py-2 px-4 rounded">
                + New notebook
            </x-link-button>
            @forelse($notebooks as $notebook)
                <div class="bg-white p-6 overflow-hidden shadow-sm sm:rounded-lg">
                    <h2 class="font-bold text-2xl text-indigo-600">
                        {{$notebook->name}}
                    </h2>
                </div>
            @empty
                <p>You have no notebooks yet.</p>
            @endforelse
            {{$notebooks->links()}}
        </div>
    </div>
</x-app-layout>
