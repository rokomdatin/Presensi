<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Presensi') - Kemenkopm</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Tailwind CSS via CDN (untuk development) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'brand-merah': '#77212f',
                        'brand-krem': '#f0e9d8',
                        'brand-biru': '#243669',
                    },
                    fontFamily: {
                        'inter': ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .main-content-bg {
            min-width: 0;
        }

        .sidebar-link.active {
            background-color: #77212f;
            color: white;
        }
        .sidebar-link:hover:not(.active) {
            background-color: rgba(119, 33, 47, 0.1);
        }

        @media (max-width: 1023px) {
            .sidebar-mobile-open {
                overflow: hidden;
            }
        }
       
    </style>
    
    @stack('styles')
</head>
<body class="bg-brand-krem min-h-screen overflow-x-hidden">
    <div class="flex min-h-screen">
        <header class="lg:hidden fixed top-0 left-0 right-0 z-30 bg-brand-biru text-white px-4 py-3 shadow-md">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <img src="{{ asset('logo_pm.png') }}" alt="Logo PM" class="w-8 h-8 object-contain">
                    <div>
                        <p class="text-sm font-semibold leading-tight">PRESENSI</p>
                        <p class="text-[11px] text-white/70 leading-tight">Kemenkopm</p>
                    </div>
                </div>
                <button id="sidebarToggle" type="button" class="inline-flex items-center justify-center w-9 h-9 rounded-md bg-white/10 hover:bg-white/20 transition" aria-label="Buka menu">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </header>

        <div id="sidebarBackdrop" class="hidden fixed inset-0 z-30 bg-black/40 lg:hidden"></div>

        <!-- Sidebar -->
        <aside id="mobileSidebar" class="w-64 bg-brand-biru min-h-screen fixed left-0 top-0 z-40 transform -translate-x-full transition-transform duration-300 lg:translate-x-0">
            <div class="p-4">
                <!-- Logo -->
                <div class="flex items-center gap-3 mb-8 pb-4 border-b border-white/20">
                    <img src="{{ asset('logo_pm.png') }}" alt="Logo PM" class="w-10 h-10 object-contain">
                    <div>
                        <h1 class="text-white font-bold text-sm leading-tight">PRESENSI</h1>
                        <p class="text-white/70 text-xs">Kemenkopm</p>
                    </div>
                </div>
                
                <!-- Navigation -->
                <nav class="space-y-1">
                    <a href="{{ route('dashboard') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-white/80 transition {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="fas fa-home w-5"></i>
                        <span>Dashboard</span>
                    </a>
                    
                    <a href="{{ route('pegawai.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-white/80 transition {{ request()->routeIs('pegawai.*') ? 'active' : '' }}">
                        <i class="fas fa-users w-5"></i>
                        <span>Data Pegawai</span>
                    </a>
                    
                    <a href="{{ route('absensi.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-white/80 transition {{ request()->routeIs('absensi.*') ? 'active' : '' }}">
                        <i class="fas fa-clock w-5"></i>
                        <span>Absensi</span>
                    </a>
                    
                    <a href="{{ route('cuti.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-white/80 transition {{ request()->routeIs('cuti.*') ? 'active' : '' }}">
                        <i class="fas fa-calendar-minus w-5"></i>
                        <span>Cuti</span>
                    </a>
                    
                    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'kepegawaian')
                    <a href="{{ route('import.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-white/80 transition {{ request()->routeIs('import.*') ? 'active' : '' }}">
                        <i class="fas fa-file-import w-5"></i>
                        <span>Import Data</span>
                    </a>
                    @endif
                    
                    @if(auth()->user()->role === 'admin')
                    <div class="pt-4 mt-4 border-t border-white/20">
                        <p class="px-4 text-xs text-white/50 uppercase tracking-wider mb-2">Admin</p>
                        <a href="{{ route('users.index') }}" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-white/80 transition {{ request()->routeIs('users.*') ? 'active' : '' }}">
                            <i class="fas fa-user-cog w-5"></i>
                            <span>Manajemen Akun</span>
                        </a>
                    </div>
                    @endif
                </nav>
            </div>
            
            <!-- User Info -->
            <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-white/20">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-brand-merah rounded-full flex items-center justify-center">
                        <span class="text-white font-bold">{{ substr(auth()->user()->nama_lengkap, 0, 1) }}</span>
                    </div>
                    <div class="flex-1">
                        <p class="text-white text-sm font-medium truncate">{{ auth()->user()->nama_lengkap }}</p>
                        <p class="text-white/60 text-xs capitalize">{{ auth()->user()->role }}</p>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-white/60 hover:text-white transition" title="Logout">
                            <i class="fas fa-sign-out-alt"></i>
                        </button>
                    </form>
                </div>
            </div>
        </aside>
        
        <!-- Main Content -->
        <main class="main-content-bg flex-1 lg:ml-64 p-4 sm:p-6 lg:p-8 pt-20 lg:pt-8">
            <!-- Flash Messages -->
            @if(session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg flex items-center gap-2">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
            @endif
            
            @if(session('error'))
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg flex items-center gap-2">
                <i class="fas fa-exclamation-circle"></i>
                {{ session('error') }}
            </div>
            @endif
            
            @yield('content')
        </main>
    </div>

    <script>
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('mobileSidebar');
        const backdrop = document.getElementById('sidebarBackdrop');

        const openSidebar = () => {
            if (!sidebar || !backdrop) return;
            sidebar.classList.remove('-translate-x-full');
            backdrop.classList.remove('hidden');
            document.body.classList.add('sidebar-mobile-open');
        };

        const closeSidebar = () => {
            if (!sidebar || !backdrop) return;
            sidebar.classList.add('-translate-x-full');
            backdrop.classList.add('hidden');
            document.body.classList.remove('sidebar-mobile-open');
        };

        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', openSidebar);
        }

        if (backdrop) {
            backdrop.addEventListener('click', closeSidebar);
        }

        if (sidebar) {
            sidebar.querySelectorAll('a').forEach((link) => {
                link.addEventListener('click', () => {
                    if (window.innerWidth < 1024) {
                        closeSidebar();
                    }
                });
            });
        }

        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                closeSidebar();
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>
