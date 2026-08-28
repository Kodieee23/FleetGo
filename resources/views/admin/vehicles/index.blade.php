@extends('layouts.app')

@section('content')
<div class="mb-8 flex flex-col md:flex-row md:items-center justify-between">
    <div>
        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Vehicle Management</h1>
        <p class="text-gray-500 dark:text-gray-400 mt-1">Manage the company fleet, track status, and remove vehicles.</p>
    </div>
    <div class="mt-4 md:mt-0" x-data>
        <button @click="$dispatch('open-modal', 'create-vehicle-modal')" class="inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-full shadow-sm transition-colors">
            <ion-icon name="car-sport-outline" class="text-xl mr-2"></ion-icon> Add New Vehicle
        </button>
    </div>
</div>

<div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
    <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between bg-gray-50/50 dark:bg-gray-800/50">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white">System Fleet</h3>
    </div>
    <div class="p-6">
        @if($vehicles->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider border-b border-gray-100 dark:border-gray-700">
                        <th class="pb-3 px-4">Name / Identifier</th>
                        <th class="pb-3 px-4">Status</th>
                        <th class="pb-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @foreach($vehicles as $vehicle)
                    <tr class="border-b border-gray-50 dark:border-gray-700/50 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <td class="py-4 px-4 font-bold text-gray-900 dark:text-white flex items-center">
                            <div class="w-10 h-10 rounded-xl bg-primary-50 text-primary-600 dark:bg-primary-900/30 dark:text-primary-400 flex items-center justify-center mr-3">
                                <ion-icon name="car" class="text-xl"></ion-icon>
                            </div>
                            {{ $vehicle->name }}
                        </td>
                        <td class="py-4 px-4">
                            @if($vehicle->status === 'available')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">Available</span>
                            @elseif($vehicle->status === 'on_trip')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">On Trip</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">Maintenance</span>
                            @endif
                        </td>
                        <td class="py-4 px-4 text-right" x-data>
                            <div class="flex items-center justify-end space-x-2">
                                <button @click="$dispatch('open-modal', 'edit-vehicle-modal-{{ $vehicle->id }}')" class="p-2 text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors" title="Edit Vehicle">
                                    <ion-icon name="create-outline" class="text-xl"></ion-icon>
                                </button>
                                
                                <form action="{{ route('admin.vehicles.destroy', $vehicle->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-gray-400 hover:text-red-600 transition-colors" title="Delete Vehicle" onclick="return confirm('Are you sure you want to permanently delete this vehicle? This action cannot be undone.')">
                                        <ion-icon name="trash-outline" class="text-xl"></ion-icon>
                                    </button>
                                </form>
                            </div>

                            <!-- Edit Modal -->
                            <template x-teleport="body">
                                <div x-data="{ show: false }"
                                     x-show="show"
                                     @open-modal.window="if ($event.detail === 'edit-vehicle-modal-{{ $vehicle->id }}') show = true"
                                     @keydown.escape.window="show = false"
                                     style="display: none;"
                                     class="fixed inset-0 z-50 overflow-y-auto"
                                     aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                        <div x-show="show" x-transition.opacity class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm transition-opacity" aria-hidden="true" @click="show = false"></div>
                                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                                        
                                        <div x-show="show" x-transition class="relative z-10 inline-block align-bottom bg-white dark:bg-gray-800 rounded-3xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full border border-gray-100 dark:border-gray-700">
                                            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between bg-gray-50/50 dark:bg-gray-800/50">
                                                <h3 class="text-lg font-bold text-gray-900 dark:text-white" id="modal-title">Edit Vehicle: {{ $vehicle->name }}</h3>
                                                <button type="button" @click="show = false" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                                                    <ion-icon name="close-outline" class="text-2xl"></ion-icon>
                                                </button>
                                            </div>
                                            <form action="{{ route('admin.vehicles.update', $vehicle->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="p-6 space-y-4">
                                                    <div>
                                                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Vehicle Name</label>
                                                        <input type="text" name="name" value="{{ $vehicle->name }}" required class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500">
                                                    </div>
                                                    <div x-data="{ status: '{{ $vehicle->status }}', statusText: '{{ $vehicle->status == 'available' ? 'Available' : ($vehicle->status == 'on_trip' ? 'On Trip' : 'Maintenance') }}', open: false }">
                                                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Status</label>
                                                        <div class="relative w-full">
                                                            <input type="hidden" name="status" x-model="status" required>
                                                            <button type="button" @click="open = !open" @click.outside="open = false" class="w-full text-left px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary-500 flex items-center justify-between shadow-sm transition-all hover:bg-gray-100 dark:hover:bg-gray-700">
                                                                <span x-text="statusText" class="text-gray-900 dark:text-white font-medium"></span>
                                                                <ion-icon name="chevron-down-outline" class="text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''"></ion-icon>
                                                            </button>
                                                            
                                                            <div x-show="open" x-transition.opacity.duration.200ms class="absolute z-50 w-full mt-2 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl shadow-xl overflow-hidden">
                                                                <div @click="status = 'available'; statusText = 'Available'; open = false" class="px-4 py-3 hover:bg-primary-50 dark:hover:bg-primary-900/30 cursor-pointer text-gray-900 dark:text-white font-medium transition-colors border-b border-gray-50 dark:border-gray-700/50">Available</div>
                                                                <div @click="status = 'on_trip'; statusText = 'On Trip'; open = false" class="px-4 py-3 hover:bg-primary-50 dark:hover:bg-primary-900/30 cursor-pointer text-gray-900 dark:text-white font-medium transition-colors border-b border-gray-50 dark:border-gray-700/50">On Trip</div>
                                                                <div @click="status = 'maintenance'; statusText = 'Maintenance'; open = false" class="px-4 py-3 hover:bg-primary-50 dark:hover:bg-primary-900/30 cursor-pointer text-gray-900 dark:text-white font-medium transition-colors">Maintenance</div>
                                                            </div>
                                                        </div>
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
            {{ $vehicles->links() }}
        </div>
        @else
        <div class="text-center py-10">
            <ion-icon name="car-sport-outline" class="text-5xl text-gray-300 dark:text-gray-600 mb-4"></ion-icon>
            <p class="text-gray-500 dark:text-gray-400">No vehicles found in the system.</p>
        </div>
        @endif
    </div>
</div>

<!-- Create Modal -->
<template x-teleport="body">
    <div x-data="{ show: false }"
         x-show="show"
         @open-modal.window="if ($event.detail === 'create-vehicle-modal') show = true"
         @keydown.escape.window="show = false"
         style="display: none;"
         class="fixed inset-0 z-50 overflow-y-auto"
         aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="show" x-transition.opacity class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm transition-opacity" aria-hidden="true" @click="show = false"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
            <div x-show="show" x-transition class="relative z-10 inline-block align-bottom bg-white dark:bg-gray-800 rounded-3xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full border border-gray-100 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between bg-gray-50/50 dark:bg-gray-800/50">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white" id="modal-title">Add New Vehicle</h3>
                    <button type="button" @click="show = false" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                        <ion-icon name="close-outline" class="text-2xl"></ion-icon>
                    </button>
                </div>
                <form action="{{ route('admin.vehicles.store') }}" method="POST">
                    @csrf
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Vehicle Name / Identifier</label>
                            <input type="text" name="name" placeholder="e.g. Toyota Hilux (GT-1020-21)" required class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500">
                        </div>
                        <div x-data="{ status: 'available', statusText: 'Available', open: false }">
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Initial Status</label>
                            <div class="relative w-full">
                                <input type="hidden" name="status" x-model="status" required>
                                <button type="button" @click="open = !open" @click.outside="open = false" class="w-full text-left px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary-500 flex items-center justify-between shadow-sm transition-all hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <span x-text="statusText" class="text-gray-900 dark:text-white font-medium"></span>
                                    <ion-icon name="chevron-down-outline" class="text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''"></ion-icon>
                                </button>
                                
                                <div x-show="open" x-transition.opacity.duration.200ms class="absolute z-50 w-full mt-2 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl shadow-xl overflow-hidden">
                                    <div @click="status = 'available'; statusText = 'Available'; open = false" class="px-4 py-3 hover:bg-primary-50 dark:hover:bg-primary-900/30 cursor-pointer text-gray-900 dark:text-white font-medium transition-colors border-b border-gray-50 dark:border-gray-700/50">Available</div>
                                    <div @click="status = 'maintenance'; statusText = 'Maintenance'; open = false" class="px-4 py-3 hover:bg-primary-50 dark:hover:bg-primary-900/30 cursor-pointer text-gray-900 dark:text-white font-medium transition-colors">Maintenance</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/30 border-t border-gray-100 dark:border-gray-700 flex justify-end space-x-3">
                        <button type="button" @click="show = false" class="px-5 py-2.5 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-bold rounded-xl shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">Cancel</button>
                        <button type="submit" class="px-5 py-2.5 bg-primary-600 text-white font-bold rounded-xl shadow-sm hover:bg-primary-700 transition-colors">Add Vehicle</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
@endsection
