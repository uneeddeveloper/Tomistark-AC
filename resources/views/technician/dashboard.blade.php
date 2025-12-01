{{-- resources/views/technician/dashboard.blade.php --}}
@extends('technician.layout')

@section('title','Dashboard Teknisi - ServisAC')
@section('header','Dashboard Teknisi')
@section('subheader','Ringkasan tugas, COD, dan pendapatan Anda')

@push('styles')
<style>
    :root {
        --sky: #0284c7;
        --sky-50: #f0f9ff;
        --sky-100: #e0f2fe;
        --indigo: #6366f1;
        --indigo-50: #eef2ff;
        --emerald: #10b981;
        --emerald-50: #ecfdf5;
    }

    .card {
        background: #fff;
        border: 1px solid rgba(2, 132, 199, .12);
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
        padding: .35rem .7rem;
        border-radius: 999px;
        font-size: .72rem;
        font-weight: 700;
        display: inline-block
    }

    /* Hero dibuat netral (tidak terlalu berwarna) */
    .hero {
        background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
        border: 1px solid #e2e8f0;
        /* slate-200 */
        border-radius: 24px;
    }

    /* util */
    .text-wrap {
        overflow-wrap: anywhere;
        word-break: break-word;
    }

    .tabular-nums {
        font-variant-numeric: tabular-nums;
    }
</style>
@endpush

@section('content')
<div class="space-y-8 -mx-6 -mt-6 p-6 lg:-mx-10 lg:-mt-10 lg:p-10 rounded-3xl"
    style="background:linear-gradient(180deg,#f2f9ff 0%,#f6fbff 60%,#f8fcff 100%);">

    {{-- HERO: ucapan selamat datang + jam digital --}}
    <div class="hero p-5 md:p-7">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">
            <div class="space-y-1 min-w-0">
                <div class="text-sm/5 text-slate-500">Selamat datang,</div>
                <h1 class="text-2xl md:text-3xl font-bold tracking-tight truncate text-slate-900">
                    {{ auth()->user()->name ?? 'Teknisi' }}
                </h1>
                <p class="text-slate-600 text-sm md:text-base text-wrap">
                    Pantau progres pekerjaan, setor COD, dan lihat pendapatan yang sudah terverifikasi.
                </p>
            </div>

            {{-- Jam Digital --}}
            <div class="w-full lg:w-auto">
                <div class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                    <div class="h-10 w-10 grid place-items-center rounded-xl bg-white text-sky-600 border border-slate-200">
                        <i class="far fa-clock"></i>
                    </div>
                    <div class="min-w-0">
                        <div id="clock-time" class="text-xl md:text-2xl font-extrabold text-slate-900 tracking-wider tabular-nums">--:--:--</div>
                        <div id="clock-date" class="text-xs md:text-sm text-slate-500 truncate">Memuat waktu…</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- STAT CARDS di bawah hero (tetap) --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
        <div class="card lift p-4 md:p-5">
            <div class="flex items-center gap-3">
                <div class="h-11 w-11 rounded-xl grid place-items-center bg-sky-50 text-sky-600">
                    <i class="fas fa-calendar-day"></i>
                </div>
                <div class="min-w-0">
                    <div class="text-xs uppercase tracking-wide text-slate-500">Tugas Hari Ini</div>
                    <div class="text-2xl font-extrabold text-slate-900">{{ $stats['tasks_today'] }}</div>
                </div>
            </div>
        </div>

        <div class="card lift p-4 md:p-5">
            <div class="flex items-center gap-3">
                <div class="h-11 w-11 rounded-xl grid place-items-center bg-indigo-50 text-indigo-600">
                    <i class="fas fa-tools"></i>
                </div>
                <div class="min-w-0">
                    <div class="text-xs uppercase tracking-wide text-slate-500">Sedang Berjalan</div>
                    <div class="text-2xl font-extrabold text-slate-900">{{ $stats['active_jobs'] }}</div>
                </div>
            </div>
        </div>

        <div class="card lift p-4 md:p-5">
            <div class="flex items-center gap-3">
                <div class="h-11 w-11 rounded-xl grid place-items-center bg-emerald-50 text-emerald-600">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="min-w-0">
                    <div class="text-xs uppercase tracking-wide text-slate-500">Selesai (Bulan Ini)</div>
                    <div class="text-2xl font-extrabold text-slate-900">{{ $stats['completed_this_month'] }}</div>
                </div>
            </div>
        </div>

        <div class="card lift p-4 md:p-5">
            <div class="flex items-center gap-3">
                <div class="h-11 w-11 rounded-xl grid place-items-center bg-sky-50 text-sky-600">
                    <i class="fas fa-wallet"></i>
                </div>
                <div class="min-w-0">
                    <div class="text-xs uppercase tracking-wide text-slate-500">Pendapatan</div>
                    <div class="text-2xl font-extrabold text-slate-900">
                        Rp {{ number_format($stats['confirmed_revenue'],0,',','.') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ====== CHARTS (tetap) ====== --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="card lift p-6 lg:col-span-2">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-slate-900">Grafik Pekerjaan (30 Hari)</h3>
                <span class="chip bg-sky-50 text-sky-700"><i class="far fa-chart-bar mr-1"></i> Live</span>
            </div>
            <div class="h-60 md:h-72"><canvas id="jobsChart"></canvas></div>
        </div>

        <div class="card lift p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-slate-900">Pendapatan Harian</h3>
                <span class="chip bg-indigo-50 text-indigo-700"><i class="fas fa-coins mr-1"></i> 30 hari</span>
            </div>
            <div class="h-60 md:h-72"><canvas id="revenueChart"></canvas></div>
        </div>
    </div>

    {{-- ====== LIST TUGAS & COD (tidak diubah fungsinya) ====== --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Tugas Aktif --}}
        <div class="card lift p-6 lg:col-span-2">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-slate-900">Tugas Aktif</h3>
                <span class="text-xs text-slate-500">Urut tanggal terdekat</span>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse($active_list as $b)
                <div class="py-4 flex items-start justify-between gap-4">
                    <div class="min-w-0">
                        <p class="font-semibold text-slate-900 truncate">
                            {{ $b->user->name ?? 'Pelanggan' }}
                            <span class="ml-2 text-xs text-slate-500">#{{ $b->id }}</span>
                        </p>
                        <p class="text-sm text-slate-600 truncate">
                            {{ $b->services->pluck('name')->join(', ') }}
                        </p>
                        <div class="mt-1 space-y-0.5 text-xs text-slate-600 text-wrap">
                            <div><i class="far fa-clock mr-1"></i>{{ $b->booking_date->format('d M Y, H:i') }} WIB</div>
                            <div><i class="fas fa-phone-alt mr-1"></i>{{ $b->user->phone_number ?? '-' }}</div>
                            <div><i class="fas fa-map-marker-alt mr-1"></i>{{ $b->user->address ?? '-' }}</div>
                        </div>
                    </div>

                    {{-- Kolom aksi responsif --}}
                    <div class="text-right space-y-2 w-44 sm:w-48 md:w-56 shrink-0">
                        <span class="chip block text-center
              @if($b->status=='in_progress') bg-indigo-50 text-indigo-700
              @elseif(in_array($b->status,['assigned','confirmed'])) bg-sky-50 text-sky-700
              @else bg-slate-100 text-slate-700 @endif">
                            {{ ucfirst(str_replace('_',' ',$b->status)) }}
                        </span>

                        @php
                        $pm = strtolower(optional($b->payment)->payment_method ?? '');
                        $isCOD = in_array($pm, ['cod','cash','cash_on_delivery','tunai']);
                        $hasProof = !empty(optional($b->payment)->payment_proof);
                        $amount = (int)(optional($b->payment)->amount ?? $b->total_price ?? 0);
                        $paymentId= optional($b->payment)->id;
                        @endphp

                        @if(in_array($b->status, ['assigned','confirmed']))
                        <form action="{{ route('technician.jobs.updateStatus', $b->id) }}" method="POST" class="inline">
                            @csrf
                            <input type="hidden" name="next_status" value="in_progress">
                            <button type="submit"
                                class="inline-flex items-center justify-center gap-2 px-3 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 text-sm w-full">
                                <i class="fas fa-play"></i> Mulai
                            </button>
                        </form>

                        @elseif($b->status === 'in_progress')
                        @if($isCOD && !$hasProof)
                        @if($paymentId)
                        <button type="button"
                            class="inline-flex items-center justify-center gap-2 px-3 py-2 rounded-lg bg-amber-600 text-white hover:bg-amber-700 text-sm w-full"
                            onclick="openCodModal({{ $paymentId }}, {{ $amount }})">
                            <i class="fas fa-money-bill-wave"></i> Setor COD
                        </button>
                        @else
                        <span class="chip bg-slate-100 text-slate-700">Tagihan belum tersedia</span>
                        @endif
                        @else
                        <form action="{{ route('technician.jobs.updateStatus', $b->id) }}" method="POST" class="inline">
                            @csrf
                            <input type="hidden" name="next_status" value="completed">
                            <button type="submit"
                                class="inline-flex items-center justify-center gap-2 px-3 py-2 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 text-sm w-full">
                                <i class="fas fa-check"></i> Selesai
                            </button>
                        </form>
                        @endif
                        @endif
                    </div>
                </div>
                @empty
                <div class="py-14 text-center">
                    <div class="inline-flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-50 text-slate-300">
                        <i class="fas fa-box-open text-2xl"></i>
                    </div>
                    <p class="mt-3 text-slate-500">Tidak ada tugas aktif.</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Manajemen COD --}}
        <div class="card lift p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-slate-900">Manajemen COD</h3>
                <a href="{{ route('technician.cod.index') }}" class="text-sky-600 hover:text-sky-700 text-sm font-semibold">Lihat semua</a>
            </div>

            <div class="space-y-3">
                @forelse($cod_pending as $p)
                <div class="border border-slate-100 rounded-xl p-3">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-slate-900 truncate">Booking #{{ $p->booking_id }}</p>
                            <p class="text-xs text-slate-500 truncate">
                                {{ $p->booking->user->name ?? 'Pelanggan' }} · {{ $p->created_at->format('d M Y') }}
                            </p>
                            <p class="text-sm font-bold text-sky-600 mt-1">Rp {{ number_format($p->amount,0,',','.') }}</p>
                            <span class="chip mt-2
                  @if($p->status=='pending') bg-amber-50 text-amber-700
                  @elseif($p->status=='pending_verification') bg-indigo-50 text-indigo-700
                  @else bg-slate-50 text-slate-700 @endif">
                                {{ str_replace('_',' ',ucfirst($p->status)) }}
                            </span>
                        </div>

                        <button
                            class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-sky-600 text-white hover:bg-sky-700 text-sm"
                            onclick="openCodModal({{ $p->id }}, {{ (int)$p->amount }})">
                            <i class="fas fa-money-bill-wave"></i> Setor
                        </button>
                    </div>
                </div>
                @empty
                <div class="rounded-xl border border-dashed border-slate-200 p-6 text-center text-slate-500">
                    Tidak ada COD menunggu tindakan.
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- Modal COD (tetap) --}}
<div id="codModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/40" onclick="closeCodModal()"></div>
    <div class="relative z-10 max-w-md w-full mx-auto mt-24 card p-6">
        <h4 class="text-lg font-semibold text-slate-900 mb-2">Setor COD</h4>
        <p class="text-sm text-slate-600 mb-4">Konfirmasi jumlah diterima & unggah bukti pembayaran (wajib untuk metode COD/tunai).</p>

        <form id="codForm" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 mb-1">Jumlah Diterima</label>
                <input type="number" name="cod_received_amount" id="cod_received_amount"
                    class="w-full rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-sky-500"
                    min="0" required>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-slate-700 mb-1">Bukti Pembayaran (gambar)</label>
                <input type="file" name="cod_proof" accept="image/*"
                    class="w-full rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-sky-500" required>
                <p class="text-xs text-slate-500 mt-1">Format: JPG/PNG/WEBP, maks 4 MB.</p>
            </div>

            <div class="flex items-center justify-end gap-2">
                <button type="button" class="px-4 py-2 rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-50" onclick="closeCodModal()">Batal</button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-sky-600 text-white hover:bg-sky-700">Setor Sekarang</button>
            </div>
        </form>
    </div>
</div>

{{-- Chart.js (tetap) --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    // ===== Jam Digital (Asia/Pontianak / WIB) =====
    (function() {
        const tz = 'Asia/Pontianak';
        const $time = document.getElementById('clock-time');
        const $date = document.getElementById('clock-date');

        function pad(n) {
            return String(n).padStart(2, '0');
        }

        function tick() {
            const now = new Date();
            // gunakan Intl agar konsisten di semua device
            const fmtTime = new Intl.DateTimeFormat('id-ID', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false,
                timeZone: tz
            }).format(now);
            const fmtDate = new Intl.DateTimeFormat('id-ID', {
                weekday: 'long',
                day: 'numeric',
                month: 'long',
                year: 'numeric',
                timeZone: tz
            }).format(now);

            if ($time) $time.textContent = fmtTime;
            if ($date) $date.textContent = `${fmtDate} • WIB`;
        }
        tick();
        setInterval(tick, 1000);
    })();

    // ===== Charts (tetap) =====
    const labels = @json($chart['labels']);
    const jobs = @json($chart['jobs']);
    const revenue = @json($chart['revenue']);

    new Chart(document.getElementById('jobsChart'), {
        type: 'line',
        data: {
            labels,
            datasets: [{
                label: 'Jobs',
                data: jobs,
                tension: .35,
                borderWidth: 2,
                borderColor: '#0284c7',
                pointRadius: 0,
                fill: true,
                backgroundColor: 'rgba(2,132,199,.12)'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    new Chart(document.getElementById('revenueChart'), {
        type: 'bar',
        data: {
            labels,
            datasets: [{
                label: 'Revenue',
                data: revenue,
                borderWidth: 0,
                backgroundColor: 'rgba(99,102,241,.7)'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        maxRotation: 0,
                        autoSkip: true
                    }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: v => 'Rp ' + new Intl.NumberFormat('id-ID').format(v)
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: (ctx) => 'Rp ' + new Intl.NumberFormat('id-ID').format(ctx.parsed.y)
                    }
                }
            }
        }
    });

    // ===== Modal COD (tetap) =====
    function openCodModal(paymentId, amount) {
        const form = document.getElementById('codForm');
        form.action = `{{ url('/technician/cod') }}/${paymentId}/collect`;
        document.getElementById('cod_received_amount').value = amount ?? 0;
        document.getElementById('codModal').classList.remove('hidden');
    }

    function closeCodModal() {
        document.getElementById('codModal').classList.add('hidden');
    }
</script>
@endsection