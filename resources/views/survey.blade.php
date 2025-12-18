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
                        <h1 class="text-2xl font-semibold text-slate-900">Surveys</h1>
                        <p class="text-sm text-slate-600">Manage existing surveys and create new ones.</p>
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
                                                <th class="px-4 py-2 text-left font-medium text-gray-700">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-100">
                                            @foreach($surveys as $surveyItem)
                                                <tr>
                                                    <td class="px-4 py-2 text-slate-900">{{ $surveyItem->title }}</td>
                                                    <td class="px-4 py-2 text-slate-700">{{ $surveyItem->start_date }}</td>
                                                    <td class="px-4 py-2 text-slate-700">{{ $surveyItem->end_date }}</td>
                                                    <td class="px-4 py-2 text-slate-700">{{ $surveyItem->is_anonymous ? 'Yes' : 'No' }}</td>
                                                    <td class="px-4 py-2 text-slate-700 space-y-2">
                                                        <div class="flex flex-wrap gap-2">
                                                            <a href="{{ route('surveys.edit', $surveyItem) }}" class="inline-flex items-center rounded-md bg-gray-800 px-3 py-1 text-xs font-semibold text-white shadow-sm hover:bg-gray-700">Edit</a>
                                                            <a href="{{ route('surveys.questions.create', $surveyItem) }}" class="inline-flex items-center rounded-md bg-green-600 hover:bg-green-700 px-3 py-1 text-xs font-semibold text-white shadow-sm" style="background-color:#16a34a;">Add question</a>
                                                            <form action="{{ route('surveys.share', $surveyItem) }}" method="POST" class="inline">
                                                                @csrf
                                                                <button type="submit" class="inline-flex items-center rounded-md bg-green-600 hover:bg-green-700 px-3 py-1 text-xs font-semibold text-white shadow-sm" style="background-color:#16a34a;">Share link</button>
                                                            </form>
                                                            <form action="{{ route('surveys.destroy', $surveyItem) }}" method="POST" class="inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="inline-flex items-center rounded-md bg-red-600 px-3 py-1 text-xs font-semibold text-white shadow-sm hover:bg-red-700">Delete</button>
                                                            </form>
                                                        </div>
                                                        @if($surveyItem->public_token)
                                                            <div class="mt-1 text-xs text-slate-600">
                                                                Public URL:
                                                                <a class="text-indigo-600 hover:underline" href="{{ url('/survey/'.$surveyItem->public_token) }}" target="_blank">
                                                                    {{ url('/survey/'.$surveyItem->public_token) }}
                                                                </a>
                                                            </div>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    @endisset

                    @isset($survey)
                        <div class="mb-8">
                            <h2 class="text-lg font-semibold text-slate-900 mb-2">Edit survey</h2>
                            <form action="{{ route('surveys.update', $survey) }}" method="POST" class="space-y-4">
                                @csrf
                                @method('PATCH')
                                <div class="space-y-1">
                                    <label for="edit_title" class="block text-sm font-medium text-slate-900">Title</label>
                                    <input
                                        id="edit_title"
                                        name="title"
                                        type="text"
                                        value="{{ old('title', $survey->title ?? '') }}"
                                        required
                                        class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm text-slate-900 focus:border-indigo-500 focus:ring-indigo-500"
                                    >
                                </div>
                                <div class="space-y-1">
                                    <label for="edit_description" class="block text-sm font-medium text-slate-900">Description</label>
                                    <textarea
                                        id="edit_description"
                                        name="description"
                                        rows="3"
                                        class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm text-slate-900 focus:border-indigo-500 focus:ring-indigo-500"
                                    >{{ old('description', $survey->description ?? '') }}</textarea>
                                </div>
                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                    <div class="space-y-1">
                                        <label for="edit_start_date" class="block text-sm font-medium text-slate-900">Start date</label>
                                        <input
                                            id="edit_start_date"
                                            name="start_date"
                                            type="datetime-local"
                                            value="{{ old('start_date', isset($survey) ? \Carbon\Carbon::parse($survey->start_date)->format('Y-m-d\TH:i') : '') }}"
                                            required
                                            class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm text-slate-900 focus:border-indigo-500 focus:ring-indigo-500"
                                        >
                                    </div>
                                    <div class="space-y-1">
                                        <label for="edit_end_date" class="block text-sm font-medium text-slate-900">End date</label>
                                        <input
                                            id="edit_end_date"
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
                                        id="edit_is_anonymous"
                                        name="is_anonymous"
                                        type="checkbox"
                                        value="1"
                                        @checked(old('is_anonymous', $survey->is_anonymous ?? false))
                                        class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                    >
                                    <label for="edit_is_anonymous" class="text-sm font-medium text-slate-900">Anonymous survey</label>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <input type="hidden" name="notify_on_answer" value="0">
                                    <input
                                        id="edit_notify_on_answer"
                                        name="notify_on_answer"
                                        type="checkbox"
                                        value="1"
                                        @checked(old('notify_on_answer', $survey->notify_on_answer ?? false))
                                        class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                    >
                                    <label for="edit_notify_on_answer" class="text-sm font-medium text-slate-900">Email me on new answer</label>
                                </div>
                                <div class="flex justify-end">
                                    <button
                                        type="submit"
                                        class="inline-flex items-center rounded-md bg-green-600 hover:bg-green-700 px-4 py-2 text-sm font-semibold text-white shadow-sm transition focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                        style="background-color:#16a34a;"
                                    >
                                        Update
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endisset

                    <h2 class="text-lg font-semibold text-slate-900 mb-2">New survey</h2>
                    <form
                        action="{{ route('surveys.store') }}"
                        method="POST"
                        class="space-y-6"
                    >
                        @csrf
                        <div class="space-y-1">
                            <label for="title" class="block text-sm font-medium text-slate-900">Title</label>
                            <input
                                id="title"
                                name="title"
                                type="text"
                                value="{{ old('title') }}"
                                required
                                class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm text-slate-900 focus:border-indigo-500 focus:ring-indigo-500"
                            >
                        </div>

                        <div class="space-y-1">
                            <label for="description" class="block text-sm font-medium text-slate-900">Description</label>
                            <textarea
                                id="description"
                                name="description"
                                rows="3"
                                class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm text-slate-900 focus:border-indigo-500 focus:ring-indigo-500"
                            >{{ old('description') }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div class="space-y-1">
                                <label for="start_date" class="block text-sm font-medium text-slate-900">Start date</label>
                                <input
                                    id="start_date"
                                    name="start_date"
                                    type="datetime-local"
                                    value="{{ old('start_date') }}"
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
                                    value="{{ old('end_date') }}"
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
                                @checked(old('is_anonymous'))
                                class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                            >
                            <label for="is_anonymous" class="text-sm font-medium text-slate-900">Anonymous survey</label>
                        </div>
                        <div class="flex items-center space-x-3">
                            <input type="hidden" name="notify_on_answer" value="0">
                            <input
                                id="notify_on_answer"
                                name="notify_on_answer"
                                type="checkbox"
                                value="1"
                                @checked(old('notify_on_answer'))
                                class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                            >
                            <label for="notify_on_answer" class="text-sm font-medium text-slate-900">Email me on new answer</label>
                        </div>

                        <div class="flex justify-end">
                            <button
                                type="submit"
                                class="inline-flex items-center rounded-md bg-green-600 hover:bg-green-700 px-4 py-2 text-sm font-semibold text-white shadow-sm transition focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                style="background-color:#16a34a;"
                            >
                                Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
