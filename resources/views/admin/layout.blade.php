<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title') - Admin ServisAC</title>

    {{-- Tailwind + Icons --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    {{-- Alpine.js + Collapse untuk dropdown --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>

    <style>
        :root {
            --brand1: #22d3ee;
            /* cyan-400 */
            --brand2: #2563eb;
            /* indigo-600 */
        }

        .sidebar {
            width: 280px;
            transition: transform .3s ease
        }

        @media (max-width:1024px) {
            .sidebar {
                transform: translateX(-100%);
                position: fixed;
                z-index: 40;
                height: 100vh
            }

            .sidebar-open {
                transform: translateX(0)
            }

            .main-content {
                width: 100%;
                padding-left: 0
            }
        }

        @media (min-width:1024px) {
            .main-content {
                width: calc(100% - 280px);
                margin-left: 280px
            }
        }

        /* Link sidebar (tema login) */
        .sidebar-link {
            display: flex;
            align-items: center;
            gap: .75rem;
            padding: .75rem 1rem;
            border-radius: 1rem;
            color: #334155;
            font-weight: 600;
            transition: all .18s ease;
        }

        .sidebar-link:hover {
            background: rgba(30, 58, 138, .06);
            color: #0f172a
        }

        .sidebar-link.active {
            color: #0b234a;
            background: linear-gradient(135deg, rgba(34, 211, 238, .15), rgba(37, 99, 235, .18));
            box-shadow: 0 10px 22px -12px rgba(2, 132, 199, .45);
            outline: 1px solid rgba(37, 99, 235, .20);
        }

        .nice-scroll::-webkit-scrollbar {
            width: 8px
        }

        .nice-scroll::-webkit-scrollbar-thumb {
            background: rgba(2, 132, 199, .25);
            border-radius: 999px
        }

        .nice-scroll::-webkit-scrollbar-track {
            background: transparent
        }
    </style>
</head>

<body class="bg-gradient-to-br from-slate-50 via-sky-50 to-white text-slate-800">
    <div class="flex">

        {{-- ================= SIDEBAR ================= --}}
        @php
        $isDash = request()->routeIs('admin.dashboard');
        $isBookings = request()->routeIs('admin.bookings.*');
        $isVerif = request()->routeIs('admin.payments.verification');
        $isPays = request()->routeIs('admin.payments.index');
        $isRefunds = request()->routeIs('admin.refunds.*');
        $isUsers = request()->routeIs('admin.users.*');
        $isServices = request()->routeIs('admin.services.*');
        $isReports = request()->routeIs('admin.reports.*');
        @endphp

        <aside id="sidebar"
            class="sidebar fixed top-0 left-0 h-screen bg-white/90 backdrop-blur border-r border-sky-100 p-6 shadow-xl lg:translate-x-0 nice-scroll overflow-y-auto"
            x-data="{
        openBooking: {{ $isBookings ? 'true' : 'false' }},
        openPayment: {{ ($isVerif || $isPays || $isRefunds) ? 'true' : 'false' }},
        openManage:  {{ ($isUsers || $isServices) ? 'true' : 'false' }},
        openReports: {{ $isReports ? 'true' : 'false' }}
      }">

            {{-- Brand --}}
            <div class="flex items-center justify-between mb-8">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                    <img src="{{ asset('img/logo.png') }}" style="width: 42px" />
                    <span class="text-2xl font-extrabold tracking-tight text-slate-900">
                        Servis<span class="bg-clip-text text-transparent" style="background-image:linear-gradient(90deg,var(--brand2),var(--brand1));">AC</span>
                    </span>
                </a>
                <button class="lg:hidden p-2 rounded-lg hover:bg-slate-100" id="closeSidebarBtn" aria-label="Tutup sidebar">
                    <i class="fas fa-times text-xl text-slate-600"></i>
                </button>
            </div>

            {{-- NAV: Kelompok + Dropdown --}}
            <nav class="space-y-2">

                {{-- Dashboard (single) --}}
                <a href="{{ route('admin.dashboard') }}"
                    class="sidebar-link {{ $isDash ? 'active' : '' }}">
                    <i class="fa-solid fa-gauge-high text-sky-700 w-5 text-center"></i>
                    <span>Dashboard</span>
                </a>

                {{-- ========== Kelompok: Booking ========== --}}
                <a href="{{ route('admin.bookings.index') }}"
                    class="sidebar-link {{ $isBookings ? 'active' : '' }}">
                    <i class="fa-solid fa-calendar-days text-sky-700 w-5 text-center"></i>
                    <span>Booking</span>
                </a>

                {{-- ========== Kelompok: Pembayaran ========== --}}
                <div class="rounded-xl">
                    <button type="button" @click="openPayment=!openPayment"
                        class="w-full sidebar-link {{ ($isVerif||$isPays||$isRefunds) ? 'active' : '' }}">
                        <i class="fa-solid fa-wallet text-sky-700 w-5 text-center"></i>
                        <span class="flex-1 text-left">Pembayaran</span>
                        <i class="fa-solid fa-chevron-down ml-auto transition"
                            :class="openPayment ? 'rotate-180' : ''"></i>
                    </button>

                    <div x-show="openPayment" x-collapse class="mt-1 pl-10 space-y-1">
                        <a href="{{ route('admin.payments.verification') }}"
                            class="flex items-center justify-between px-3 py-2 rounded-lg text-sm {{ $isVerif ? 'bg-sky-50 text-sky-900 ring-1 ring-sky-200' : 'text-slate-600 hover:bg-slate-100' }}">
                            <span>Verifikasi Pembayaran</span>
                            @if(isset($pendingVerificationCount) && $pendingVerificationCount>0)
                            <span class="ml-3 bg-amber-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $pendingVerificationCount }}</span>
                            @endif
                        </a>

                        <a href="{{ route('admin.payments.index') }}"
                            class="block px-3 py-2 rounded-lg text-sm {{ $isPays ? 'bg-sky-50 text-sky-900 ring-1 ring-sky-200' : 'text-slate-600 hover:bg-slate-100' }}">
                            Riwayat Pembayaran
                        </a>

                        <a href="{{ route('admin.refunds.index') }}"
                            class="flex items-center justify-between px-3 py-2 rounded-lg text-sm {{ $isRefunds ? 'bg-sky-50 text-sky-900 ring-1 ring-sky-200' : 'text-slate-600 hover:bg-slate-100' }}">
                            <span>Permintaan Refund</span>
                            @if(isset($pendingRefundCount) && $pendingRefundCount>0)
                            <span class="ml-3 bg-rose-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $pendingRefundCount }}</span>
                            @endif
                        </a>
                    </div>
                </div>

                {{-- ========== Kelompok: Manajemen ========== --}}
                <div class="rounded-xl">
                    <button type="button" @click="openManage=!openManage"
                        class="w-full sidebar-link {{ ($isUsers || $isServices) ? 'active' : '' }}">
                        <i class="fa-solid fa-briefcase text-sky-700 w-5 text-center"></i>
                        <span class="flex-1 text-left">Manajemen</span>
                        <i class="fa-solid fa-chevron-down ml-auto transition"
                            :class="openManage ? 'rotate-180' : ''"></i>
                    </button>

                    <div x-show="openManage" x-collapse class="mt-1 pl-10 space-y-1">
                        <a href="{{ route('admin.users.index') }}"
                            class="block px-3 py-2 rounded-lg text-sm {{ $isUsers ? 'bg-sky-50 text-sky-900 ring-1 ring-sky-200' : 'text-slate-600 hover:bg-slate-100' }}">
                            Pengguna
                        </a>
                        <a href="{{ route('admin.services.index') }}"
                            class="block px-3 py-2 rounded-lg text-sm {{ $isServices ? 'bg-sky-50 text-sky-900 ring-1 ring-sky-200' : 'text-slate-600 hover:bg-slate-100' }}">
                            Layanan
                        </a>
                    </div>
                </div>

                {{-- ========== Kelompok: Laporan ========== --}}
                <a href="{{ route('admin.reports.index') }}"
                    class="sidebar-link {{ $isReports ? 'active' : '' }}">
                    <i class="fa-solid fa-calendar-days text-sky-700 w-5 text-center"></i>
                    <span>Laporan</span>
                </a>


                {{-- Logout (tetap) --}}
                <div class="mt-6">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="w-full flex items-center justify-center py-2.5 px-4 rounded-xl text-slate-700 ring-1 ring-slate-200/70 bg-white hover:bg-rose-50 hover:text-rose-600 transition">
                            <i class="fas fa-sign-out-alt mr-2"></i>
                            <span class="font-semibold">Keluar</span>
                        </button>
                    </form>
                </div>
            </nav>
        </aside>

        {{-- Overlay mobile --}}
        <div id="sidebarOverlay" class="fixed inset-0 bg-black/40 backdrop-blur-sm z-30 hidden lg:hidden"></div>

        {{-- ================= MAIN ================= --}}
        <div class="main-content flex-1">
            {{-- Header (tetap) --}}
            <header class="bg-white/80 backdrop-blur border-b border-sky-100 sticky top-0 z-30">
                <div class="relative">
                    <div class="absolute inset-x-0 -top-0.5 h-1" style="background:linear-gradient(90deg,var(--brand1),var(--brand2))"></div>
                </div>
                <div class="flex items-center justify-between h-20 px-6 lg:px-10">
                    <button class="lg:hidden p-2 rounded-lg hover:bg-slate-100" id="openSidebarBtn" aria-label="Buka sidebar">
                        <i class="fas fa-bars text-xl text-slate-600"></i>
                    </button>
                    <div class="flex-1">
                        <h1 class="text-xl font-semibold text-gray-900">@yield('header')</h1>
                        <p class="text-sm text-gray-600">@yield('subheader')</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        @yield('header-actions')
                    </div>
                </div>
            </header>

            {{-- Content --}}
            <main class="p-6 lg:p-10">
                @if(session('success'))
                <div class="_alert bg-green-50 border border-green-200 rounded-xl p-4 mb-6">
                    <div class="flex items-center">
                        <div class="bg-green-100 p-2 rounded-full mr-3">
                            <i class="fas fa-check-circle text-green-600"></i>
                        </div>
                        <p class="text-green-800 font-medium">{{ session('success') }}</p>
                    </div>
                </div>
                @endif

                @if(session('error'))
                <div class="_alert bg-rose-50 border border-rose-200 rounded-xl p-4 mb-6">
                    <div class="flex items-center">
                        <div class="bg-rose-100 p-2 rounded-full mr-3">
                            <i class="fas fa-exclamation-circle text-rose-600"></i>
                        </div>
                        <p class="text-rose-800 font-medium">{{ session('error') }}</p>
                    </div>
                </div>
                @endif

                @if ($errors->any())
                <div class="_alert bg-rose-50 border border-rose-200 rounded-xl p-4 mb-6">
                    <p class="font-bold text-rose-800">Oops! Ada kesalahan:</p>
                    <ul class="list-disc list-inside text-rose-700">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @yield('content')
            </main>

            {{-- Footer (tetap) --}}
            <footer class="bg-white/80 backdrop-blur border-t mt-12 border-sky-100">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <p class="text-gray-500 text-sm text-center">&copy; {{ date('Y') }} ServisAC. Hak cipta dilindungi.</p>
                </div>
            </footer>
        </div>
    </div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const openSidebarBtn = document.getElementById('openSidebarBtn');
        const closeSidebarBtn = document.getElementById('closeSidebarBtn');

        function openSidebar() {
            sidebar.classList.add('sidebar-open');
            overlay.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeSidebar() {
            sidebar.classList.remove('sidebar-open');
            overlay.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        openSidebarBtn && openSidebarBtn.addEventListener('click', openSidebar);
        closeSidebarBtn && closeSidebarBtn.addEventListener('click', closeSidebar);
        overlay && overlay.addEventListener('click', closeSidebar);

        // Auto-hide alerts
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                document.querySelectorAll('._alert').forEach(el => {
                    el.style.transition = 'opacity .5s ease';
                    el.style.opacity = '0';
                    setTimeout(() => el.remove(), 500);
                })
            }, 5000);
        });
    </script>

    @stack('scripts')
</body>

</html>