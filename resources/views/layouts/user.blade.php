<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ServisAC - Panel Pengguna')</title>

    {{-- Tailwind & Font Awesome --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    {{-- Alpine.js untuk menu mobile --}}
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>

    <style>
        :root {
            --blue: #3b82f6;
            --indigo: #2563eb;
            --cyan: #06b6d4;
        }

        /* ===== Tema latar (selaras dengan login) ===== */
        body {
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif;
            min-height: 100vh;
            background:
                radial-gradient(1200px 900px at 10% 8%, #ffffffaa 0%, #ffffff00 60%),
                radial-gradient(900px 600px at 92% 16%, #bfe8ff66 0%, #ffffff00 55%),
                linear-gradient(140deg, #e6f7ff, #dff4ff 50%, #cfe9ff);
        }

        /* ===== Sidebar, Header, Footer dibuat glassy ===== */
        .sidebar-surface {
            background: linear-gradient(180deg, #ffffffee, #fffffff7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            box-shadow: 0 24px 60px rgba(14, 51, 97, .16);
            border-right: 1px solid #e9f2ff;
        }

        .header-surface,
        .footer-surface {
            background: linear-gradient(180deg, #ffffffee, #fffffff8);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-color: #e9f2ff !important;
        }

        /* ===== Link aktif di sidebar (chip gradient) ===== */
        .sidebar-link {
            border-radius: 0.875rem;
            /* 14px */
            transition: .18s ease;
        }

        .sidebar-link:hover {
            background: #eef7ff;
            transform: translateX(2px);
        }

        .sidebar-link.active {
            color: #0f172a;
            background: linear-gradient(90deg, #e8f2ff, #e6fbff);
            box-shadow: inset 0 0 0 1px #cfe3ff, 0 6px 14px rgba(59, 130, 246, .15);
            font-weight: 700;
        }

        .sidebar-icon {
            color: #9aa7b5
        }

        .sidebar-link.active .sidebar-icon {
            color: #2563eb
        }

        /* Tombol keluar */
        .btn-logout {
            border-radius: .875rem;
            border: 1px solid #fee2e2;
            background: #fff;
            transition: .18s ease;
        }

        .btn-logout:hover {
            background: #fff1f1;
            border-color: #fecaca;
            color: #dc2626;
            transform: translateY(-1px);
            box-shadow: 0 10px 18px rgba(220, 38, 38, .08);
        }

        /* Kartu konten utama */
        .card-surface {
            background: #fff;
            border: 1px solid #eaf2ff;
            border-radius: 1.25rem;
            box-shadow: 0 16px 40px rgba(15, 23, 42, .06);
        }

        /* Notifikasi: pakai atribut class_alert yang sudah ada */
        [class_alert] {
            display: block;
            border-radius: .875rem;
        }
    </style>
</head>

<body>

    <div x-data="{ sidebarOpen: false }">

        <!-- ===== SIDEBAR (logo tetap) ===== -->
        <aside
            class="fixed inset-y-0 left-0 z-40 w-64 shadow-xl transform -translate-x-full transition-transform duration-300 ease-in-out lg:translate-x-0 sidebar-surface"
            :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}">
            <div class="p-6 flex items-center justify-center border-b border-[#e9f2ff]">
                <a href="{{ route('user.dashboard') }}" class="flex items-center space-x-3">
                    <!-- LOGO TETAP dipakai -->
                    <img src="{{ asset('img/logo.png') }}" class="rounded h-10 w-10 object-contain" alt="ServisAC">
                    <span class="text-2xl font-extrabold bg-clip-text text-transparent"
                        style="background-image:linear-gradient(90deg,#2563eb,#06b6d4);">ServisAC</span>
                </a>
            </div>

            <nav class="mt-4 px-4">
                <a href="{{ route('user.dashboard') }}"
                    class="sidebar-link flex items-center px-4 py-3 text-slate-700 {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                    <i class="sidebar-icon fas fa-tachometer-alt w-6 text-center mr-3"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('user.bookings') }}"
                    class="sidebar-link flex items-center px-4 py-3 mt-2 text-slate-700 {{ request()->routeIs('user.bookings*') ? 'active' : '' }}">
                    <i class="sidebar-icon fas fa-calendar-alt w-6 text-center mr-3"></i>
                    <span>Booking Saya</span>
                </a>
                <a href="{{ route('user.payments') }}"
                    class="sidebar-link flex items-center px-4 py-3 mt-2 text-slate-700 {{ request()->routeIs('user.payments*') ? 'active' : '' }}">
                    <i class="sidebar-icon fas fa-wallet w-6 text-center mr-3"></i>
                    <span>Pembayaran Saya</span>
                </a>
                <a href="{{ route('user.profile') }}"
                    class="sidebar-link flex items-center px-4 py-3 mt-2 text-slate-700 {{ request()->routeIs('user.profile*') ? 'active' : '' }}">
                    <i class="sidebar-icon fas fa-user-circle w-6 text-center mr-3"></i>
                    <span>Profil</span>
                </a>
            </nav>

            <div class="absolute bottom-4 left-4 right-4">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center px-4 py-3 text-slate-700 btn-logout">
                        <i class="fas fa-sign-out-alt w-6 text-center mr-3"></i>
                        <span class="font-semibold">Keluar</span>
                    </button>
                </form>
            </div>
        </aside>

        {{-- Overlay mobile --}}
        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-30 bg-black/50 lg:hidden" x-cloak></div>

        <!-- ===== AREA KONTEN ===== -->
        <div class="flex-1 flex flex-col lg:ml-64">

            <!-- ===== HEADER (dipertahankan) ===== -->
            <header class="bg-white shadow-sm border-b header-surface sticky top-0 z-20">
                <div class="flex items-center justify-between h-16 px-6">
                    <button @click.stop="sidebarOpen = !sidebarOpen" class="lg:hidden text-slate-700 hover:text-slate-900">
                        <i class="fas fa-bars text-xl"></i>
                    </button>

                    <div class="hidden lg:block">
                        <h1 class="text-xl font-extrabold bg-clip-text text-transparent"
                            style="background-image:linear-gradient(90deg,#2563eb,#06b6d4);">
                            @yield('header-title', 'Dashboard')
                        </h1>
                    </div>

                    <div class="flex items-center ml-auto gap-3">
                        <span class="text-slate-700 font-medium hidden sm:block">
                            Selamat datang, {{ Auth::user()->name }}!
                        </span>
                        <!-- avatar kecil agar menyatu dengan tema -->
                        <div class="h-9 w-9 rounded-xl grid place-items-center"
                            style="background:linear-gradient(135deg,#dff4ff,#bde7ff); box-shadow:inset 0 0 0 2px #fff;">
                            <i class="fa-solid fa-snowflake text-[#2563eb]"></i>
                        </div>
                    </div>
                </div>
            </header>

            <!-- ===== MAIN ===== -->
            <main class="flex-1 p-6 lg:p-10">
                {{-- Notifikasi --}}
                @if(session('success'))
                <div class_alert="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl mb-6 shadow-sm">
                    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                </div>
                @endif

                @if(session('error'))
                <div class_alert="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 shadow-sm">
                    <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
                </div>
                @endif

                @if ($errors->any())
                <div class_alert="bg-red-50 border border-red-200 text-red-700 px-4 py-4 rounded-xl mb-6 shadow-sm">
                    <p class="font-bold mb-2">Oops! Terjadi kesalahan:</p>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                {{-- Judul Halaman (Mobile) --}}
                <div class="lg:hidden mb-6">
                    <h1 class="text-2xl font-extrabold bg-clip-text text-transparent"
                        style="background-image:linear-gradient(90deg,#2563eb,#06b6d4);">
                        @yield('header-title', 'Dashboard')
                    </h1>
                </div>

                {{-- Konten halaman (dibuat seperti kartu) --}}
                <div class="card-surface p-4 sm:p-6 lg:p-8">
                    @yield('content')
                </div>
            </main>

            <!-- ===== FOOTER (dipertahankan) ===== -->
            <footer class="bg-white border-t footer-surface mt-auto">
                <div class="max-w-7xl mx-auto px-6 py-4 text-center text-slate-600">
                    <p>&copy; {{ date('Y') }} ServisAC. Hak cipta dilindungi.</p>
                </div>
            </footer>
        </div>
    </div>

    <script>
        // Auto-hide alerts: tetap mendukung atribut class_alert
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const alerts = document.querySelectorAll('[class_alert]');
                alerts.forEach(alert => {
                    alert.style.transition = 'opacity .5s ease';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                });
            }, 5000);
        });
    </script>

    @stack('scripts')
</body>

</html>