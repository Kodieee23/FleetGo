<!DOCTYPE html>
<html lang="en" class="antialiased">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - FleetGo</title>
    <!-- Premium Font: Outfit -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans min-h-screen flex items-center justify-center relative overflow-hidden bg-background-light dark:bg-background-dark transition-colors duration-300">

    <!-- Premium Background Gradient Orbs -->
    <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-primary-500/50 rounded-full filter blur-3xl opacity-80 animate-blob dark:bg-primary-600/40"></div>
    <div class="absolute top-[20%] right-[-10%] w-96 h-96 bg-purple-300/50 rounded-full filter blur-3xl opacity-80 animate-blob animation-delay-2000 dark:bg-purple-900/40"></div>
    <div class="absolute bottom-[-20%] left-[20%] w-[500px] h-[500px] bg-blue-200/60 rounded-full filter blur-3xl opacity-80 animate-blob animation-delay-4000 dark:bg-blue-900/40"></div>

    <!-- Theme Toggle (Absolute top right) -->
    <button onclick="document.documentElement.classList.toggle('dark')" class="absolute top-6 right-6 w-12 h-12 rounded-full bg-white/60 dark:bg-gray-800/60 backdrop-blur-md shadow-sm text-gray-600 dark:text-gray-300 hover:text-primary-900 flex items-center justify-center transition-all hover:scale-105 z-50 border border-white/30">
        <ion-icon name="moon-outline" class="text-2xl"></ion-icon>
    </button>

    <!-- Glassmorphic Login Card -->
    <div class="relative w-full max-w-md p-10 m-4 rounded-3xl glass z-10 flex flex-col items-center">
        
        <!-- Stylish Logo -->
        <div class="mb-10 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-primary-600 to-primary-900 text-white shadow-lg mb-4 transform -rotate-6 hover:rotate-0 transition-transform duration-300">
                <ion-icon name="car-sport" class="text-3xl"></ion-icon>
            </div>
            <h1 class="text-4xl tracking-tighter text-gray-900 dark:text-white logo-font font-black">
                Fleet<span class="text-primary-600 dark:text-primary-400">Go</span>
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2 font-medium tracking-wide">Fleet Management System</p>
        </div>

        @if($errors->any())
            <div class="w-full bg-red-50 text-red-600 p-4 rounded-xl mb-6 text-sm font-medium border border-red-100 flex items-start dark:bg-red-900/30 dark:border-red-800/50 dark:text-red-400">
                <ion-icon name="alert-circle-outline" class="text-xl mr-2 flex-shrink-0"></ion-icon>
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="w-full space-y-6">
            @csrf
            
            <!-- Sleek Input: Username -->
            <div class="relative">
                <input type="text" id="username" name="username" class="w-full px-4 py-3 bg-white/50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:bg-white dark:focus:bg-gray-800 transition-all" placeholder="Username" required value="{{ old('username') }}">
            </div>

            <!-- Sleek Input: Password -->
            <div class="relative">
                <input type="password" id="password" name="password" class="w-full px-4 py-3 bg-white/50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:bg-white dark:focus:bg-gray-800 transition-all" placeholder="Password" required>
            </div>

            <div class="flex items-center justify-between mt-2">
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="w-4 h-4 rounded text-primary-600 focus:ring-primary-500 border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                    <span class="text-sm text-gray-600 dark:text-gray-400 font-medium">Remember me</span>
                </label>
                <a href="#" class="text-sm font-semibold text-primary-600 hover:text-primary-700 dark:text-primary-400 transition-colors">Forgot Password?</a>
            </div>

            <button type="submit" class="w-full py-3.5 px-4 bg-primary-900 hover:bg-primary-800 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-0.5 focus:outline-none focus:ring-4 focus:ring-primary-500/50">
                Sign In to Dashboard
            </button>
        </form>
        
        <p class="mt-8 text-xs text-gray-400 dark:text-gray-500 font-medium text-center">
            &copy; {{ date('Y') }} FleetGo. All rights reserved.
        </p>
    </div>

</body>
</html>
