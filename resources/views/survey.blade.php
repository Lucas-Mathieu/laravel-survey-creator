<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Surveys') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-slate-900">
                    <header class="mb-6">
                        <h1 class="text-2xl font-semibold text-slate-900">Create a survey</h1>
                        <p class="text-sm text-slate-600">Fill in the survey details and save.</p>
                    </header>

                    @isset($surveys)
                        <div class="mb-8">
                            <h2 class="text-lg font-semibold text-slate-900 mb-2">Existing surveys</h2>
                            @if($surveys->isEmpty())
                                <p class="text-sm text-slate-600">No surveys available.</p>
                            @else
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-4 py-2 text-left font-medium text-gray-700">Title</th>
                                                <th class="px-4 py-2 text-left font-medium text-gray-700">Start</th>
                                                <th class="px-4 py-2 text-left font-medium text-gray-700">End</th>
                                                <th class="px-4 py-2 text-left font-medium text-gray-700">Anonymous</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-100">
                                            @foreach($surveys as $survey)
                                        <tr>
                                            <td class="px-4 py-2 text-slate-900">{{ $survey->title }}</td>
                                            <td class="px-4 py-2 text-slate-700">{{ $survey->start_date }}</td>
                                            <td class="px-4 py-2 text-slate-700">{{ $survey->end_date }}</td>
                                            <td class="px-4 py-2 text-slate-700">{{ $survey->is_anonymous ? 'Yes' : 'No' }}</td>
                                            <td class="px-4 py-2 text-slate-700">
                                                <a href="{{ route('surveys.edit', $survey) }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">Edit</a>
                                                <form action="{{ route('surveys.destroy', $survey) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="ml-2 text-sm text-red-600 hover:text-red-800 font-medium">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                        </div>
                    @endisset

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

                    <form
                        action="{{ isset($survey) ? route('surveys.update', $survey) : route('surveys.store') }}"
                        method="POST"
                        class="space-y-6"
                    >
                        @csrf
                        @isset($survey)
                            @method('PATCH')
                        @endisset
                        <div class="space-y-1">
                            <label for="title" class="block text-sm font-medium text-slate-900">Title</label>
                            <input
                                id="title"
                                name="title"
                                type="text"
                                value="{{ old('title', $survey->title ?? '') }}"
                                required
                                class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm text-slate-900 focus:border-indigo-500 focus:ring-indigo-500"
                            >
                        </div>

                        <div class="space-y-1">
                            <label for "description" class="block text-sm font-medium text-slate-900">Description</label>
                            <textarea
                                id="description"
                                name="description"
                                rows="3"
                                class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm text-slate-900 focus:border-indigo-500 focus:ring-indigo-500"
                            >{{ old('description', $survey->description ?? '') }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div class="space-y-1">
                                <label for="start_date" class="block text-sm font-medium text-slate-900">Start date</label>
                                <input
                                    id="start_date"
                                    name="start_date"
                                    type="datetime-local"
                                    value="{{ old('start_date', isset($survey) ? \Carbon\Carbon::parse($survey->start_date)->format('Y-m-d\TH:i') : '') }}"
                                    required
                                    class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm text-slate-900 focus:border-indigo-500 focus:ring-indigo-500"
                                >
                            </div>
                            <div class="space-y-1">
                                <label for="end_date" class="block text-sm font-medium text-slate-900">End date</label>
                                <input
                                    id="end_date"
                                    name="end_date"
                                    type="datetime-local"
                                    value="{{ old('end_date', isset($survey) ? \Carbon\Carbon::parse($survey->end_date)->format('Y-m-d\TH:i') : '') }}"
                                    required
                                    class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm text-slate-900 focus:border-indigo-500 focus:ring-indigo-500"
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
                                @checked(old('is_anonymous', $survey->is_anonymous ?? false))
                                class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                            >
                            <label for="is_anonymous" class="text-sm font-medium text-slate-900">Anonymous survey</label>
                        </div>

                        <div class="flex justify-end">
                            <button
                                type="submit"
                                class="inline-flex items-center rounded-md bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                            >
                                {{ isset($survey) ? 'Update' : 'Save' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
