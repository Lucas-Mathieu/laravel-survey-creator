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

                    <form class="space-y-4">
                        <div class="rounded-md border border-dashed border-gray-300 p-4 text-sm text-slate-600">
                            Questions will appear here once they are available.
                        </div>
                        <div class="flex justify-end">
                            <button type="button" class="inline-flex items-center rounded-md bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-sm" disabled>
                                Submit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
