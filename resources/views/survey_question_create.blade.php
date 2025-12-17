<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add a question') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-slate-900">
                    <header class="mb-6">
                        <h1 class="text-2xl font-semibold text-slate-900">Add a question</h1>
                        <p class="text-sm text-slate-600">Create a new question for: <strong>{{ $survey->title }}</strong></p>
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

                    <form action="{{ route('surveys.questions.store', $survey) }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="survey_id" value="{{ $survey->id }}">

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
                            <label for="question_type" class="block text-sm font-medium text-slate-900">Question type</label>
                            <select id="question_type" name="question_type" required class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm text-slate-900 focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="text" {{ old('question_type') == 'text' ? 'selected' : '' }}>Text</option>
                                <option value="scale_1_10" {{ old('question_type') == 'scale_1_10' ? 'selected' : '' }}>Scale 1-10</option>
                                <option value="single_choice" {{ old('question_type') == 'single_choice' ? 'selected' : '' }}>Single choice (radio)</option>
                                <option value="multiple_choice" {{ old('question_type') == 'multiple_choice' ? 'selected' : '' }}>Multiple choice (checkbox)</option>
                            </select>
                        </div>
                        <div class="space-y-1">
                            <label for="data" class="block text-sm font-medium text-slate-900">Options (JSON array) for choice questions</label>
                            <textarea
                                id="data"
                                name="data"
                                rows="4"
                                class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm text-slate-900 focus:border-indigo-500 focus:ring-indigo-500"
                            >{{ old('data') }}</textarea>
                            <p class="text-xs text-slate-500">Example: ["Option A", "Option B", "Option C"]</p>
                        </div>

                        <div class="flex justify-end">
                            <button
                                type="submit"
                                class="inline-flex items-center rounded-md bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                            >
                                Add question
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
