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
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h1 class="text-2xl font-semibold text-gray-900">Liste des sondages</h1>
                            <p class="text-sm text-gray-600">Sondages de tes organisations.</p>
                        </div>
                        <a href="{{ route('surveys.create') }}"
                           class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            Nouveau sondage
                        </a>
                    </div>

                    @if(session('status'))
                        <div class="mb-4 rounded-md bg-green-50 p-4 text-sm text-green-700">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if($surveys->isEmpty())
                        <p class="text-sm text-gray-600">Aucun sondage disponible.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 text-sm">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left font-medium text-gray-700">Titre</th>
                                        <th class="px-4 py-2 text-left font-medium text-gray-700">Début</th>
                                        <th class="px-4 py-2 text-left font-medium text-gray-700">Fin</th>
                                        <th class="px-4 py-2 text-left font-medium text-gray-700">Anonyme</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($surveys as $survey)
                                        <tr>
                                            <td class="px-4 py-2 text-gray-900">{{ $survey->title }}</td>
                                            <td class="px-4 py-2 text-gray-700">{{ $survey->start_date }}</td>
                                            <td class="px-4 py-2 text-gray-700">{{ $survey->end_date }}</td>
                                            <td class="px-4 py-2 text-gray-700">
                                                {{ $survey->is_anonymous ? 'Oui' : 'Non' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
