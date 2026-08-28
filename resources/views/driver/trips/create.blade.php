@extends('layouts.app')

@section('content')
<div class="mb-8">
    <a href="{{ route('driver.dashboard') }}" class="inline-flex items-center text-sm font-semibold text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-300 mb-4 transition-colors">
        <ion-icon name="arrow-back-outline" class="mr-1"></ion-icon> Back to Dashboard
    </a>
    <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Log New Trip</h1>
    <p class="text-gray-500 dark:text-gray-400 mt-1">Enter your trip details. You can add multiple stops.</p>
</div>

<div class="bg-white dark:bg-gray-800 rounded-3xl p-6 md:p-10 shadow-sm border border-gray-100 dark:border-gray-700 max-w-3xl">
    <form action="{{ route('driver.trips.store') }}" method="POST" x-data="tripForm()">
        @csrf
        
        <div class="space-y-6">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Vehicle Custom Dropdown -->
                <div class="relative">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Vehicle</label>
                    <div x-data="{ open: false }" class="relative w-full">
                        <input type="hidden" name="vehicle_id" x-model="vehicleId" required>
                        <button type="button" @click="open = !open" @click.outside="open = false" class="w-full text-left pl-11 pr-4 py-3 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary-500 flex items-center justify-between shadow-sm transition-all hover:bg-gray-100 dark:hover:bg-gray-700">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-primary-500">
                                <ion-icon name="car-sport" class="text-xl"></ion-icon>
                            </div>
                            <span x-text="vehicleText" :class="vehicleId === '' ? 'text-gray-500 dark:text-gray-400' : 'text-gray-900 dark:text-white font-medium'"></span>
                            <ion-icon name="chevron-down-outline" class="text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''"></ion-icon>
                        </button>
                        
                        <div x-show="open" x-transition.opacity.duration.200ms class="absolute z-50 w-full mt-2 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl shadow-xl max-h-60 overflow-y-auto overflow-x-hidden">
                            <div @click="vehicleId = ''; vehicleText = 'Select a vehicle...'; open = false" class="px-4 py-3 hover:bg-primary-50 dark:hover:bg-primary-900/30 cursor-pointer text-gray-500 border-b border-gray-50 dark:border-gray-700/50 transition-colors">Select a vehicle...</div>
                            @foreach($vehicles as $vehicle)
                            <div @click="vehicleId = '{{ $vehicle->id }}'; vehicleText = '{{ addslashes($vehicle->name) }}'; open = false" class="px-4 py-3 hover:bg-primary-50 dark:hover:bg-primary-900/30 cursor-pointer text-gray-900 dark:text-white flex flex-col transition-colors border-b border-gray-50 dark:border-gray-700/50 last:border-0">
                                <span class="font-bold">{{ $vehicle->name }}</span>
                                <span class="text-xs text-green-600 dark:text-green-400 font-medium tracking-wide">Available</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Department Custom Dropdown -->
                <div class="relative">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Requesting Department</label>
                    <div x-data="{ open: false }" class="relative w-full">
                        <input type="hidden" name="department_id" x-model="departmentId" required>
                        <button type="button" @click="open = !open" @click.outside="open = false" class="w-full text-left pl-11 pr-4 py-3 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary-500 flex items-center justify-between shadow-sm transition-all hover:bg-gray-100 dark:hover:bg-gray-700">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-indigo-500">
                                <ion-icon name="business" class="text-xl"></ion-icon>
                            </div>
                            <span x-text="departmentText" :class="departmentId === '' ? 'text-gray-500 dark:text-gray-400' : 'text-gray-900 dark:text-white font-medium'"></span>
                            <ion-icon name="chevron-down-outline" class="text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''"></ion-icon>
                        </button>
                        
                        <div x-show="open" x-transition.opacity.duration.200ms class="absolute z-50 w-full mt-2 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl shadow-xl max-h-60 overflow-y-auto">
                            <div @click="departmentId = ''; departmentText = 'Select department...'; open = false" class="px-4 py-3 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 cursor-pointer text-gray-500 border-b border-gray-50 dark:border-gray-700/50 transition-colors">Select department...</div>
                            @foreach($departments as $dept)
                            <div @click="departmentId = '{{ $dept->id }}'; departmentText = '{{ addslashes($dept->name) }}'; open = false" class="px-4 py-3 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 cursor-pointer text-gray-900 dark:text-white font-medium transition-colors border-b border-gray-50 dark:border-gray-700/50 last:border-0">
                                {{ $dept->name }}
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Purpose Custom Dropdown -->
            <div class="relative">
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Trip Purpose</label>
                <div x-data="{ open: false }" class="relative w-full">
                    <input type="hidden" name="purpose_id" x-model="purposeId" required>
                    <button type="button" @click="open = !open" @click.outside="open = false" class="w-full text-left pl-11 pr-4 py-3 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-primary-500 flex items-center justify-between shadow-sm transition-all hover:bg-gray-100 dark:hover:bg-gray-700">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-purple-500">
                            <ion-icon name="briefcase" class="text-xl"></ion-icon>
                        </div>
                        <span x-text="purposeText" :class="purposeId === '' ? 'text-gray-500 dark:text-gray-400' : 'text-gray-900 dark:text-white font-medium'"></span>
                        <ion-icon name="chevron-down-outline" class="text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''"></ion-icon>
                    </button>
                    
                    <div x-show="open" x-transition.opacity.duration.200ms class="absolute z-50 w-full mt-2 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-xl shadow-xl max-h-60 overflow-y-auto">
                        <div @click="setPurpose('', 'Select purpose...'); open = false" class="px-4 py-3 hover:bg-purple-50 dark:hover:bg-purple-900/30 cursor-pointer text-gray-500 border-b border-gray-50 dark:border-gray-700/50 transition-colors">Select purpose...</div>
                        @foreach($purposes as $purpose)
                        <div @click="setPurpose('{{ $purpose->id }}', '{{ addslashes($purpose->name) }}'); open = false" class="px-4 py-3 hover:bg-purple-50 dark:hover:bg-purple-900/30 cursor-pointer text-gray-900 dark:text-white font-medium transition-colors border-b border-gray-50 dark:border-gray-700/50 last:border-0">
                            {{ $purpose->name }}
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Other Purpose Description (Conditional) -->
            <div x-show="showOtherDescription" x-transition class="bg-purple-50 dark:bg-purple-900/20 p-5 rounded-2xl border border-purple-100 dark:border-purple-800">
                <label class="block text-sm font-bold text-purple-900 dark:text-purple-300 mb-2">Please describe the specific purpose</label>
                <input type="text" name="other_purpose_description" class="w-full px-4 py-3 bg-white dark:bg-gray-800 border border-purple-200 dark:border-purple-700 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 shadow-inner" placeholder="e.g. Delivering emergency supplies">
            </div>

            <!-- Final Destination -->
            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Final Destination</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-red-500">
                        <ion-icon name="location" class="text-xl"></ion-icon>
                    </div>
                    <input type="text" name="destination" required class="w-full pl-11 pr-4 py-3 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 shadow-sm" placeholder="Where is the final stop?">
                </div>
            </div>

            <hr class="border-gray-100 dark:border-gray-700 my-8">

            <!-- Multi-Stops Section -->
            <div class="bg-gray-50 dark:bg-gray-800/50 p-6 rounded-2xl border border-gray-100 dark:border-gray-700">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 gap-3">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white flex flex-wrap items-center">
                        <ion-icon name="git-commit-outline" class="mr-2 text-primary-500"></ion-icon> Additional Stops <span class="text-gray-400 text-sm font-normal ml-2">(Optional)</span>
                    </h3>
                    <button type="button" @click="addStop" class="inline-flex justify-center items-center px-4 py-2 w-full sm:w-auto bg-white dark:bg-gray-700 hover:bg-primary-50 dark:hover:bg-gray-600 text-primary-600 dark:text-primary-400 text-sm font-bold rounded-xl border border-gray-200 dark:border-gray-600 shadow-sm transition-all hover:shadow">
                        <ion-icon name="add-outline" class="mr-1 text-lg"></ion-icon> Add Stop
                    </button>
                </div>
                
                <div class="space-y-3" x-show="stops.length > 0">
                    <template x-for="(stop, index) in stops" :key="index">
                        <div class="flex items-center space-x-3 animate-fade-in group">
                            <div class="w-8 h-8 rounded-full bg-primary-100 dark:bg-primary-900/40 flex items-center justify-center text-primary-700 dark:text-primary-400 font-bold text-xs shadow-inner" x-text="index + 1"></div>
                            <input type="text" x-model="stops[index]" :name="'stops['+index+']'" class="flex-1 px-4 py-3 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-primary-500 shadow-sm" placeholder="Enter stop location (e.g., Bank Branch)">
                            <button type="button" @click="removeStop(index)" class="w-12 h-12 flex items-center justify-center text-red-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-xl transition-all border border-transparent hover:border-red-100 dark:hover:border-red-800/50">
                                <ion-icon name="trash" class="text-xl"></ion-icon>
                            </button>
                        </div>
                    </template>
                </div>
                
                <div x-show="stops.length === 0" class="text-center py-6">
                    <p class="text-sm text-gray-500 dark:text-gray-400">No additional stops. You will drive directly to the final destination.</p>
                </div>
            </div>

            <div class="pt-6">
                <button type="submit" class="w-full py-4 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all flex items-center justify-center text-lg group">
                    <ion-icon name="rocket-outline" class="mr-3 text-2xl group-hover:translate-x-1 transition-transform"></ion-icon> Start Trip Now
                </button>
            </div>
            
        </div>
    </form>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('tripForm', () => ({
        vehicleId: '',
        vehicleText: 'Select a vehicle...',
        
        departmentId: '',
        departmentText: 'Select department...',
        
        purposeId: '',
        purposeText: 'Select purpose...',
        
        stops: [],
        
        get showOtherDescription() {
            return this.purposeText.toLowerCase().includes('other');
        },
        
        setPurpose(id, text) {
            this.purposeId = id;
            this.purposeText = text;
        },
        
        addStop() {
            this.stops.push('');
        },
        
        removeStop(index) {
            this.stops.splice(index, 1);
        }
    }))
})
</script>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fadeIn 0.3s ease-out forwards;
    }
</style>
@endsection
