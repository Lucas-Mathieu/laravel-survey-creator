<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Survey') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-slate-900">
                    <h1 class="text-2xl font-semibold text-slate-900 mb-2">{{ $survey->title }}</h1>
                    <p class="text-sm text-slate-600 mb-6">Please fill out the survey below.</p>

                    @if (session('status'))
                        <div class="mb-4 rounded-md bg-blue-50 p-4 text-sm text-blue-700">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('surveys.answers.store', $survey) }}" class="space-y-6">
                        @csrf
                        <input type="hidden" name="survey_id" value="{{ $survey->id }}">

                        @forelse($questions as $index => $question)
                            <div class="rounded-md border border-gray-200 p-4">
                                <div class="font-medium text-slate-900 mb-2">{{ $question->title }}</div>
                                <input type="hidden" name="answers[{{ $index }}][question_id]" value="{{ $question->id }}">

                                @if($question->question_type === 'text')
                                    <textarea
                                        name="answers[{{ $index }}][answer]"
                                        rows="3"
                                        class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm text-slate-900 focus:border-indigo-500 focus:ring-indigo-500"
                                        required
                                    ></textarea>
                                @elseif($question->question_type === 'single_choice')
                                    <div class="space-y-2">
                                        @foreach(($question->data ?? []) as $option)
                                            <label class="flex items-center space-x-2 text-sm text-slate-700">
                                                <input type="radio" name="answers[{{ $index }}][answer]" value="{{ $option }}" required>
                                                <span>{{ $option }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                @elseif($question->question_type === 'multiple_choice')
                                    <div class="space-y-2">
                                        @foreach(($question->data ?? []) as $option)
                                            <label class="flex items-center space-x-2 text-sm text-slate-700">
                                                <input type="checkbox" name="answers[{{ $index }}][answer][]" value="{{ $option }}">
                                                <span>{{ $option }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                @elseif($question->question_type === 'scale_1_10')
                                    <select
                                        name="answers[{{ $index }}][answer]"
                                        class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm text-slate-900 focus:border-indigo-500 focus:ring-indigo-500"
                                        required
                                    >
                                        <option value="">Select a value</option>
                                        @for($i = 1; $i <= 10; $i++)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                @endif
                            </div>
                        @empty
                            <div class="rounded-md border border-dashed border-gray-300 p-4 text-sm text-slate-600">
                                No questions available for this survey.
                            </div>
                        @endforelse

                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex items-center rounded-md bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-sm">
                                Submit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
