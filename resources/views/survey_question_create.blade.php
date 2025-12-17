<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ajouter une question') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-slate-900">
                    <header class="mb-6">
                        <h1 class="text-2xl font-semibold text-slate-900">Ajouter une question</h1>
                        <p class="text-sm text-slate-600">Créez une nouvelle question pour l'enquête : <strong>{{ $survey->title }}</strong></p>
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
                            <label for="title" class="block text-sm font-medium text-slate-900">Titre</label>
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
                            <label for="question_type" class="block text-sm font-medium text-slate-900">Type of question</label>
                            <select id="question_type" name="question_type" required class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm text-slate-900 focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="text" {{ old('question_type') == 'text' ? 'selected' : '' }}>Text</option>
                                <option value="scale_from_1_to_10" {{ old('question_type') == 'scale_from_1_to_10' ? 'selected' : '' }}>scale from 1 to 10</option>
                                <option value="multiple_choice" {{ old('question_type') == 'multiple_choice' ? 'selected' : '' }}>multiple choice</option>
                                <option value="unique_choice" {{ old('question_type') == 'unique_choice' ? 'selected' : '' }}>unique choice</option>
                            </select>
                        </div>
                        <div class="space-y-1">
                            <label for="options" class="block text-sm font-medium text-slate-900">Options (une par ligne ou séparées par des virgules)</label>
                            <textarea
                                id="options"
                                name="options"
                                rows="4"
                                placeholder="Option 1,Option 2,Option 3  ou  une option par ligne"
                                class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm text-slate-900 focus:border-indigo-500 focus:ring-indigo-500"
                            >{{ old('options') }}</textarea>
                        </div>

                        <div class="flex justify-end">
                            <button
                                type="submit"

                                >
                                add the question
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
