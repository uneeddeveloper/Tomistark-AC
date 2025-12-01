<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Panel Teknisi') - ServisAC</title>

    {{-- Tailwind + Font Awesome --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

    <style>
        :root {
            --sidew: 280px;
        }

        .sidebar {
            width: var(--sidew);
        }

        @media (min-width: 1024px) {
            .content-pad {
                padding-left: var(--sidew);
            }
        }

        /* Kartu & utilitas kecil (dipakai semua halaman teknisi) */
        .card {
            background: #fff;
            border: 1px solid rgba(14, 165, 233, .14);
            border-radius: 18px;
            box-shadow: 0 8px 24px rgba(2, 132, 199, .08)
        }

        .lift {
            transition: transform .15s ease, box-shadow .15s ease
        }

        .lift:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(2, 132, 199, .12)
        }

        .chip {
            padding: .25rem .6rem;
            border-radius: 999px;
            font-size: .75rem;
            font-weight: 600;
            display: inline-block
        }

        .glass {
            background: linear-gradient(180deg, #f2f9ff 0%, #f6fbff 60%, #f8fcff 100%);
        }
    </style>

    @stack('styles')
</head>

<body class="bg-slate-50 text-slate-900">

    {{-- SIDEBAR --}}
    <aside id="sidebar"
        class="sidebar fixed inset-y-0 left-0 z-40 bg-white border-r border-slate-200 px-5 py-6
                transform -translate-x-full lg:translate-x-0 transition-transform">
        <div class="flex items-center gap-3 px-1 mb-8">
            <img src="{{ asset('img/logo.png') }}" class="w-10 h-10 rounded" alt="logo">
            <div>
                <p class="font-extrabold text-xl tracking-tight">ServisAC</p>
                <p class="text-[13px] text-slate-500 -mt-1">Panel Teknisi</p>
            </div>
            <button class="ml-auto lg:hidden text-slate-500" onclick="toggleSidebar(false)">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>

        <nav class="space-y-2">
            <a href="{{ route('technician.dashboard') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold
               {{ request()->routeIs('technician.dashboard') ? 'bg-sky-600 text-white' : 'text-slate-700 hover:bg-slate-100' }}">
                <i class="fa-solid fa-gauge-high"></i>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('technician.cod.index') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold
               {{ request()->routeIs('technician.cod.*') ? 'bg-sky-600 text-white' : 'text-slate-700 hover:bg-slate-100' }}">
                <i class="fa-solid fa-money-bill-trend-up"></i>
                <span>Manajemen COD</span>
            </a>
        </nav>

        <form action="{{ route('logout') }}" method="POST" class="absolute left-5 right-5 bottom-5">
            @csrf
            <button type="submit"
                class="w-full mt-6 px-4 py-2.5 rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-50">
                <i class="fa-solid fa-right-from-bracket mr-2"></i> Keluar
            </button>
        </form>
    </aside>

    {{-- CONTENT WRAPPER --}}
    <div class="content-pad min-h-screen lg:pl-[var(--sidew)]">
        {{-- HEADER --}}
        <header class="sticky top-0 z-30 bg-white/80 backdrop-blur border-b border-slate-200">
            <div class="h-20 flex items-center gap-4 px-5 lg:px-10">
                <button class="lg:hidden text-slate-600" onclick="toggleSidebar(true)">
                    <i class="fa-solid fa-bars text-2xl"></i>
                </button>
                <div class="flex-1">
                    <h1 class="text-2xl font-extrabold tracking-tight">@yield('header','Dashboard')</h1>
                    <p class="text-sm text-slate-500">@yield('subheader','')</p>
                </div>
                <div>@yield('header-actions')</div>
            </div>
        </header>

        {{-- PAGE BODY --}}
        <main class="p-5 lg:p-10 glass">
            @yield('content')
        </main>

        <footer class="bg-white border-t">
            <div class="px-5 lg:px-10 py-6 text-center text-slate-500 text-sm">
                &copy; {{ date('Y') }} ServisAC · Panel Teknisi
            </div>
        </footer>
    </div>

    <script>
        function toggleSidebar(open) {
            const el = document.getElementById('sidebar');
            if (open) {
                el.classList.remove('-translate-x-full');
            } else {
                el.classList.add('-translate-x-full');
            }
        }
        // tutup jika klik di luar sidebar pada mobile
        document.addEventListener('click', (e) => {
            const s = document.getElementById('sidebar');
            const inSidebar = s.contains(e.target);
            const openingBtn = e.target.closest('button[onclick^="toggleSidebar(true)"]');
            if (!inSidebar && !openingBtn && window.innerWidth < 1024) {
                s.classList.add('-translate-x-full');
            }
        });
    </script>

    @stack('scripts')
</body>

</html>