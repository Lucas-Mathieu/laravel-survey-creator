<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Surveys') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <header class="mb-6">
                        <h1 class="text-2xl font-semibold text-gray-900">Créer un sondage</h1>
                        <p class="text-sm text-gray-600">Renseigne les infos du sondage puis enregistre.</p>
                    </header>

                    @if (session('status'))
                        <div class="mb-4 rounded-md bg-green-50 p-4 text-sm text-green-700">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-4 rounded-md bg-red-50 p-4 text-sm text-red-700 space-y-1">
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    <form action="{{ route('surveys.store') }}" method="POST" class="space-y-6">
                        @csrf
                        <div class="space-y-1">
                            <label for="title" class="block text-sm font-medium text-gray-800">Titre</label>
                            <input
                                id="title"
                                name="title"
                                type="text"
                                value="{{ old('title') }}"
                                required
                                class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                        </div>

                        <div class="space-y-1">
                            <label for="description" class="block text-sm font-medium text-gray-800">Description</label>
                            <textarea
                                id="description"
                                name="description"
                                rows="3"
                                class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >{{ old('description') }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div class="space-y-1">
                                <label for="start_date" class="block text-sm font-medium text-gray-800">Date de début</label>
                                <input
                                    id="start_date"
                                    name="start_date"
                                    type="datetime-local"
                                    value="{{ old('start_date') }}"
                                    required
                                    class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                >
                            </div>
                            <div class="space-y-1">
                                <label for="end_date" class="block text-sm font-medium text-gray-800">Date de fin</label>
                                <input
                                    id="end_date"
                                    name="end_date"
                                    type="datetime-local"
                                    value="{{ old('end_date') }}"
                                    required
                                    class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                >
                            </div>
                        </div>

                        <div class="flex items-center space-x-3">
                            <input type="hidden" name="is_anonymous" value="0">
                            <input
                                id="is_anonymous"
                                name="is_anonymous"
                                type="checkbox"
                                value="1"
                                @checked(old('is_anonymous'))
                                class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                            >
                            <label for="is_anonymous" class="text-sm font-medium text-gray-800">Sondage anonyme</label>
                        </div>

                        <div class="flex justify-end">
                            <button
                                type="submit"
                                class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                            >
                                Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
