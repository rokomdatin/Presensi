<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Absensi Kemenkopm</title>
    
    {{-- diossyaban: Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    {{-- diossyaban: Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    {{-- diossyaban: Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // diossyaban: Tailwind config
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
    
    {{-- diossyaban: Custom Styles --}}
    <style>
        /* diossyaban: body background */
        body {
            font-family: 'Montserrat', sans-serif;
            background-image: url('{{ asset("IMG_7768.JPG") }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        /* diossyaban: overlay */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(36, 54, 105, 0.3);
            z-index: 0;
            pointer-events: none;
        }
        /* diossyaban: fade in from top */
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        /* diossyaban: fade in from bottom */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        /* diossyaban: animation classes */
        .fade-logo {
            animation: fadeInDown 0.8s ease-out;
        }
        .fade-title {
            animation: fadeInDown 1s ease-out 0.2s both;
        }
        .fade-subtitle {
            animation: fadeInDown 1s ease-out 0.3s both;
        }
        .fade-form {
            animation: fadeInUp 1s ease-out 0.4s both;
        }
    </style>
</head>
{{-- diossyaban: body --}}
<body class="min-h-screen flex items-center justify-center relative">
    <div class="w-full max-w-md p-8 relative z-10">
        {{-- diossyaban: Header --}}
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-white drop-shadow-lg fade-title">PRESENSI</h1>
            <p class="text-white drop-shadow-lg mt-2 text-sm fade-subtitle">Kementerian Koordinator Pemberdayaan Masyarakat</p>
        </div>
        
        {{-- diossyaban: Login Form --}}
        <div class="bg-white rounded-2xl shadow-xl p-8 fade-form">
            <h2 class="text-xl font-semibold text-brand-biru mb-6">Masuk ke Akun</h2>
            
            {{-- diossyaban: Error Messages --}}
            @if($errors->any())
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg text-sm">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
            @endif
            
            {{-- diossyaban: Form --}}
            <form action="{{ route('login') }}" method="POST">
                @csrf
                
                {{-- diossyaban: Username Field --}}
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
                
                {{-- diossyaban: Password Field --}}
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
                
                {{-- diossyaban: Remember Me --}}
                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember" class="w-4 h-4 text-brand-biru border-gray-300 rounded focus:ring-brand-biru">
                        <span class="ml-2 text-sm text-gray-600">Ingat saya</span>
                    </label>
                </div>
                
                {{-- diossyaban: Submit Button --}}
                <button type="submit" class="w-full bg-brand-merah hover:bg-brand-merah/90 text-white font-semibold py-3 px-4 rounded-lg transition duration-200">
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    Masuk
                </button>
            </form>
        </div>
        
        {{-- diossyaban: Footer --}}
        <p class="text-center text-gray-500 text-sm mt-6">
            &copy; {{ date('Y') }} Kementerian Koordinator Pemberdayaan Masyarakat
        </p>
    </div>
</body>
</html>
