<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Absensi Kemenkopm</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'brand-merah': '#77212f',
                        'brand-krem': '#f0e9d8',
                        'brand-biru': '#243669',
                    }
                }
            }
        }
    </script>
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-brand-krem min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md p-8">
        <!-- Logo & Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-brand-biru rounded-2xl mb-4">
                <i class="fas fa-building-columns text-white text-3xl"></i>
            </div>
            <h1 class="text-2xl font-bold text-brand-biru">Absensi Kemenkopm</h1>
            <p class="text-gray-600 mt-2">Kementerian Koordinator Pemberdayaan Masyarakat</p>
        </div>
        
        <!-- Login Form -->
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <h2 class="text-xl font-semibold text-brand-biru mb-6">Masuk ke Akun</h2>
            
            @if($errors->any())
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg text-sm">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
            @endif
            
            <form action="{{ route('login') }}" method="POST">
                @csrf
                
                <div class="mb-4">
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                            <i class="fas fa-user"></i>
                        </span>
                        <input type="text" 
                               name="username" 
                               id="username" 
                               value="{{ old('username') }}"
                               class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-biru focus:border-transparent transition"
                               placeholder="Masukkan username"
                               required>
                    </div>
                </div>
                
                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input type="password" 
                               name="password" 
                               id="password" 
                               class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-biru focus:border-transparent transition"
                               placeholder="Masukkan password"
                               required>
                    </div>
                </div>
                
                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember" class="w-4 h-4 text-brand-biru border-gray-300 rounded focus:ring-brand-biru">
                        <span class="ml-2 text-sm text-gray-600">Ingat saya</span>
                    </label>
                </div>
                
                <button type="submit" class="w-full bg-brand-merah hover:bg-brand-merah/90 text-white font-semibold py-3 px-4 rounded-lg transition duration-200">
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    Masuk
                </button>
            </form>
        </div>
        
        <!-- Footer -->
        <p class="text-center text-gray-500 text-sm mt-6">
            &copy; {{ date('Y') }} Kementerian Koordinator Pemberdayaan Masyarakat
        </p>
    </div>
</body>
</html>
