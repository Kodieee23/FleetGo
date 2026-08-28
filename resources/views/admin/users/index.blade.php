@extends('layouts.app')

@section('content')
<div class="mb-8 flex flex-col md:flex-row md:items-center justify-between">
    <div>
        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">User Management</h1>
        <p class="text-gray-500 dark:text-gray-400 mt-1">Manage drivers, managers, and system administrators.</p>
    </div>
    <div class="mt-4 md:mt-0" x-data>
        <button @click="$dispatch('open-modal', 'create-user-modal')" class="inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-full shadow-sm transition-colors">
            <ion-icon name="person-add-outline" class="text-xl mr-2"></ion-icon> Add New User
        </button>
    </div>
</div>

<div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
    <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between bg-gray-50/50 dark:bg-gray-800/50">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white">System Users</h3>
    </div>
    <div class="p-6">
        @if($users->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider border-b border-gray-100 dark:border-gray-700">
                        <th class="pb-3 px-4">Name</th>
                        <th class="pb-3 px-4">Username</th>
                        <th class="pb-3 px-4">Role</th>
                        <th class="pb-3 px-4">Status</th>
                        <th class="pb-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @foreach($users as $user)
                    <tr class="border-b border-gray-50 dark:border-gray-700/50 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <td class="py-4 px-4 font-bold text-gray-900 dark:text-white flex items-center">
                            <div class="w-8 h-8 rounded-full bg-primary-100 text-primary-700 flex items-center justify-center font-bold mr-3">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            {{ $user->name }}
                        </td>
                        <td class="py-4 px-4 text-gray-600 dark:text-gray-300">{{ $user->username }}</td>
                        <td class="py-4 px-4">
                            @if($user->role === 'admin')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400">Admin</span>
                            @elseif($user->role === 'manager')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">Manager</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">Driver</span>
                            @endif
                        </td>
                        <td class="py-4 px-4">
                            @if($user->is_active)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">Active</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">Inactive</span>
                            @endif
                        </td>
                        <td class="py-4 px-4 text-right" x-data>
                            <div class="flex items-center justify-end space-x-2">
                                <button @click="$dispatch('open-modal', 'edit-user-modal-{{ $user->id }}')" class="p-2 text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors" title="Edit User">
                                    <ion-icon name="create-outline" class="text-xl"></ion-icon>
                                </button>
                                
                                @if($user->id !== auth()->id())
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-gray-400 hover:text-{{ $user->is_active ? 'red' : 'green' }}-600 transition-colors" title="{{ $user->is_active ? 'Deactivate' : 'Reactivate' }} User" onclick="return confirm('Are you sure you want to {{ $user->is_active ? 'deactivate' : 'reactivate' }} this user?')">
                                        <ion-icon name="{{ $user->is_active ? 'ban' : 'checkmark-circle' }}-outline" class="text-xl"></ion-icon>
                                    </button>
                                </form>
                                @endif
                            </div>

                            <!-- Edit Modal for this User -->
                            <template x-teleport="body">
                                <div x-data="{ show: false }"
                                     x-show="show"
                                     @open-modal.window="if ($event.detail === 'edit-user-modal-{{ $user->id }}') show = true"
                                     @keydown.escape.window="show = false"
                                     style="display: none;"
                                     class="fixed inset-0 z-50 overflow-y-auto"
                                     aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                        <div x-show="show" x-transition.opacity class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm transition-opacity" aria-hidden="true" @click="show = false"></div>
                                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                                        
                                        <div x-show="show" x-transition class="relative z-10 inline-block align-bottom bg-white dark:bg-gray-800 rounded-3xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full border border-gray-100 dark:border-gray-700">
                                            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between bg-gray-50/50 dark:bg-gray-800/50">
                                                <h3 class="text-lg font-bold text-gray-900 dark:text-white" id="modal-title">Edit User: {{ $user->name }}</h3>
                                                <button type="button" @click="show = false" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                                                    <ion-icon name="close-outline" class="text-2xl"></ion-icon>
                                                </button>
                                            </div>
                                            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="p-6 space-y-4">
                                                    <div>
                                                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Full Name</label>
                                                        <input type="text" name="name" value="{{ $user->name }}" required class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500">
                                                    </div>
                                                    <div>
                                                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Username</label>
                                                        <input type="text" name="username" value="{{ $user->username }}" required class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500">
                                                    </div>
                                                    <div x-data="{ role: '{{ $user->role }}', roleText: '{{ ucfirst($user->role) }}', open: false }">
                                                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Role</label>
                                                        <div class="relative w-full">
                                                            <input type="hidden" name="role" x-model="role" required>
                                                            <button type="button" @click="open = !open" @click.outside="open = false" class="w-full text-left px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary-500 flex items-center justify-between shadow-sm transition-all hover:bg-gray-100 dark:hover:bg-gray-700">
                                                                <span x-text="roleText" class="text-gray-900 dark:text-white font-medium"></span>
                                                                <ion-icon name="chevron-down-outline" class="text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''"></ion-icon>
                                                            </button>
                                                            
                                                            <div x-show="open" x-transition.opacity.duration.200ms class="absolute z-50 w-full mt-2 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl shadow-xl overflow-hidden">
                                                                <div @click="role = 'driver'; roleText = 'Driver'; open = false" class="px-4 py-3 hover:bg-primary-50 dark:hover:bg-primary-900/30 cursor-pointer text-gray-900 dark:text-white font-medium transition-colors border-b border-gray-50 dark:border-gray-700/50">Driver</div>
                                                                <div @click="role = 'manager'; roleText = 'Manager'; open = false" class="px-4 py-3 hover:bg-primary-50 dark:hover:bg-primary-900/30 cursor-pointer text-gray-900 dark:text-white font-medium transition-colors border-b border-gray-50 dark:border-gray-700/50">Manager</div>
                                                                <div @click="role = 'admin'; roleText = 'Admin'; open = false" class="px-4 py-3 hover:bg-primary-50 dark:hover:bg-primary-900/30 cursor-pointer text-gray-900 dark:text-white font-medium transition-colors">Admin</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">New Password <span class="text-xs text-gray-500 font-normal">(Leave blank to keep current)</span></label>
                                                        <input type="password" name="password" class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500" placeholder="••••••••">
                                                    </div>
                                                </div>
                                                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/30 border-t border-gray-100 dark:border-gray-700 flex justify-end space-x-3">
                                                    <button type="button" @click="show = false" class="px-5 py-2.5 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-bold rounded-xl shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">Cancel</button>
                                                    <button type="submit" class="px-5 py-2.5 bg-primary-600 text-white font-bold rounded-xl shadow-sm hover:bg-primary-700 transition-colors">Save Changes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </template>

                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="mt-6">
            {{ $users->links() }}
        </div>
        @else
        <div class="text-center py-10">
            <ion-icon name="people-outline" class="text-5xl text-gray-300 dark:text-gray-600 mb-4"></ion-icon>
            <p class="text-gray-500 dark:text-gray-400">No users found.</p>
        </div>
        @endif
    </div>
</div>

<!-- Create Modal -->
<template x-teleport="body">
    <div x-data="{ show: false }"
         x-show="show"
         @open-modal.window="if ($event.detail === 'create-user-modal') show = true"
         @keydown.escape.window="show = false"
         style="display: none;"
         class="fixed inset-0 z-50 overflow-y-auto"
         aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="show" x-transition.opacity class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm transition-opacity" aria-hidden="true" @click="show = false"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
            <div x-show="show" x-transition class="relative z-10 inline-block align-bottom bg-white dark:bg-gray-800 rounded-3xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full border border-gray-100 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between bg-gray-50/50 dark:bg-gray-800/50">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white" id="modal-title">Create New User</h3>
                    <button type="button" @click="show = false" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                        <ion-icon name="close-outline" class="text-2xl"></ion-icon>
                    </button>
                </div>
                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Full Name</label>
                            <input type="text" name="name" required class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Username</label>
                            <input type="text" name="username" required class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500">
                        </div>
                        <div x-data="{ role: 'driver', roleText: 'Driver', open: false }">
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Role</label>
                            <div class="relative w-full">
                                <input type="hidden" name="role" x-model="role" required>
                                <button type="button" @click="open = !open" @click.outside="open = false" class="w-full text-left px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary-500 flex items-center justify-between shadow-sm transition-all hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <span x-text="roleText" class="text-gray-900 dark:text-white font-medium"></span>
                                    <ion-icon name="chevron-down-outline" class="text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''"></ion-icon>
                                </button>
                                
                                <div x-show="open" x-transition.opacity.duration.200ms class="absolute z-50 w-full mt-2 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl shadow-xl overflow-hidden">
                                    <div @click="role = 'driver'; roleText = 'Driver'; open = false" class="px-4 py-3 hover:bg-primary-50 dark:hover:bg-primary-900/30 cursor-pointer text-gray-900 dark:text-white font-medium transition-colors border-b border-gray-50 dark:border-gray-700/50">Driver</div>
                                    <div @click="role = 'manager'; roleText = 'Manager'; open = false" class="px-4 py-3 hover:bg-primary-50 dark:hover:bg-primary-900/30 cursor-pointer text-gray-900 dark:text-white font-medium transition-colors border-b border-gray-50 dark:border-gray-700/50">Manager</div>
                                    <div @click="role = 'admin'; roleText = 'Admin'; open = false" class="px-4 py-3 hover:bg-primary-50 dark:hover:bg-primary-900/30 cursor-pointer text-gray-900 dark:text-white font-medium transition-colors">Admin</div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Initial Password</label>
                            <input type="password" name="password" required class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500">
                        </div>
                    </div>
                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/30 border-t border-gray-100 dark:border-gray-700 flex justify-end space-x-3">
                        <button type="button" @click="show = false" class="px-5 py-2.5 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-bold rounded-xl shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">Cancel</button>
                        <button type="submit" class="px-5 py-2.5 bg-primary-600 text-white font-bold rounded-xl shadow-sm hover:bg-primary-700 transition-colors">Create User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
@endsection
