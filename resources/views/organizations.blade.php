<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Organizations') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (session('status'))
                        <div class="mb-4 text-sm text-green-600">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-4 text-sm text-red-600">
                            <ul class="list-disc pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mb-6">
                        <h3 class="font-semibold text-lg mb-2">Create an organization</h3>
                        <form method="POST" action="{{ route('organizations.store') }}" class="space-y-2">
                            @csrf
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Organization name</label>
                                <input type="text" name="name" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm bg-white px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="e.g. My Team" required>
                            </div>
                            <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded shadow" style="background-color:#16a34a;">Create</button>
                        </form>
                    </div>

                    <div>
                        <h3 class="font-semibold text-lg mb-2">My organizations</h3>
                        @forelse($organizations ?? [] as $organization)
                            <div class="border rounded p-4 mb-4">
                                <div class="flex items-center justify-between">
                                    <div class="font-semibold">{{ $organization->name }}</div>
                                    <div class="flex items-center space-x-3">
                                        <form method="POST" action="{{ route('organizations.active') }}">
                                            @csrf
                                            <input type="hidden" name="organization_id" value="{{ $organization->id }}">
                                            <button type="submit" class="text-sm {{ ($activeOrganizationId ?? null) == $organization->id ? 'text-green-700 font-semibold' : 'text-indigo-600 hover:underline' }}">
                                                {{ ($activeOrganizationId ?? null) == $organization->id ? 'Active' : 'Set active' }}
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('organizations.destroy', $organization) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline">Delete</button>
                                        </form>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <form method="POST" action="{{ route('organizations.update', $organization) }}" class="flex items-center space-x-2">
                                        @csrf
                                        @method('PATCH')
                                        <input type="text" name="name" value="{{ $organization->name }}" class="border border-gray-300 rounded-md shadow-sm bg-white px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="New name" required>
                                        <button type="submit" class="px-3 py-1 bg-gray-800 text-white rounded shadow">Rename</button>
                                    </form>
                                </div>

                                <div class="mt-4">
                                    <h4 class="font-medium">Add a member</h4>
                                    <form method="POST" action="{{ route('organizations.members.store', $organization) }}" class="space-y-2">
                                        @csrf
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">User</label>
                                            <select name="user_id" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm bg-white px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                                                <option value="">Select a user</option>
                                                @foreach($users ?? [] as $user)
                                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Role</label>
                                            <select name="role" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm bg-white px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                                                <option value="member">Member</option>
                                                <option value="admin">Admin</option>
                                            </select>
                                        </div>
                                        <button type="submit" class="px-3 py-1 bg-green-600 hover:bg-green-700 text-white rounded shadow" style="background-color:#16a34a;">Add</button>
                                    </form>
                                </div>

                                <div class="mt-4">
                                    <h4 class="font-medium">Remove a member</h4>
                                    <form method="POST" action="{{ route('organizations.members.destroy', [$organization, 0]) }}" onsubmit="this.action=this.action.replace('/0', '/' + this.user_id.value)" class="space-y-2">
                                        @csrf
                                        @method('DELETE')
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">User</label>
                                            <select name="user_id" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm bg-white px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                                                @foreach(($organizationMembers[$organization->id] ?? collect()) as $member)
                                                    @php
                                                        $u = $member->user;
                                                    @endphp
                                                    @if($u)
                                                        <option value="{{ $member->user_id }}">
                                                            {{ trim(($u->first_name ?? '') . ' ' . ($u->last_name ?? '')) }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded shadow">Remove</button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <p>No organization yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
