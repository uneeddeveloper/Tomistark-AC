@extends('layouts.user')

@section('title', 'Pembayaran Saya - ServisAC')
@section('header-title', 'Pembayaran Saya')

@section('content')
@php
use Illuminate\Pagination\AbstractPaginator;

// Ambil koleksi pada halaman ini (agar aman baik paginator maupun collection)
$pageCollection = ($payments instanceof AbstractPaginator) ? $payments->getCollection() : collect($payments);

$totalPaidAmount = $pageCollection->where('status','paid')->sum('amount');
$pendingVerifCount = $pageCollection->where('status','pending_verification')->count();
$paidCount = $pageCollection->where('status','paid')->count();

// Siapkan data untuk chart (berdasarkan data yang tampil di halaman ini)
$chartPayload = $pageCollection->map(function($p){
return [
'id' => $p->id,
'booking_id' => $p->booking_id,
'amount' => (float) $p->amount,
'status' => (string) $p->status,
'payment_type' => (string) $p->payment_type,
'created_at' => \Carbon\Carbon::parse($p->created_at)->toIso8601String(),
];
})->values();
@endphp

{{-- ======= STAT CARDS ======= --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="rounded-2xl overflow-hidden border border-sky-100 shadow-sm bg-white/80">
        <div class="h-1 w-full" style="background:linear-gradient(90deg,#22d3ee,#2563eb)"></div>
        <div class="p-6">
            <div class="flex items-center gap-4">
                <div class="h-12 w-12 rounded-xl grid place-items-center text-sky-700"
                    style="background:linear-gradient(135deg,#dff4ff,#bde7ff); box-shadow:inset 0 0 0 2px #fff;">
                    <i class="fas fa-wallet"></i>
                </div>
                <div>
                    <p class="text-sm text-slate-500">Total Pengeluaran (halaman ini)</p>
                    <p class="text-2xl font-extrabold text-slate-900">
                        Rp {{ number_format($totalPaidAmount, 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="rounded-2xl overflow-hidden border border-amber-100 shadow-sm bg-white/80">
        <div class="h-1 w-full" style="background:linear-gradient(90deg,#fde68a,#f59e0b)"></div>
        <div class="p-6">
            <div class="flex items-center gap-4">
                <div class="h-12 w-12 rounded-xl grid place-items-center text-amber-700"
                    style="background:linear-gradient(135deg,#fff6e6,#ffedd1); box-shadow:inset 0 0 0 2px #fff;">
                    <i class="fas fa-clock"></i>
                </div>
                <div>
                    <p class="text-sm text-slate-500">Menunggu Verifikasi</p>
                    <p class="text-2xl font-extrabold text-slate-900">{{ $pendingVerifCount }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="rounded-2xl overflow-hidden border border-emerald-100 shadow-sm bg-white/80">
        <div class="h-1 w-full" style="background:linear-gradient(90deg,#86efac,#10b981)"></div>
        <div class="p-6">
            <div class="flex items-center gap-4">
                <div class="h-12 w-12 rounded-xl grid place-items-center text-emerald-700"
                    style="background:linear-gradient(135deg,#e9fbf3,#d3f7e6); box-shadow:inset 0 0 0 2px #fff;">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div>
                    <p class="text-sm text-slate-500">Pembayaran Lunas</p>
                    <p class="text-2xl font-extrabold text-slate-900">{{ $paidCount }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ======= CHARTS ======= --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    {{-- Line Chart: Pengeluaran per Bulan (data di halaman ini) --}}
    <div class="lg:col-span-2 rounded-2xl border border-sky-100 bg-white/80 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-sky-100 flex items-center justify-between">
            <h3 class="text-lg font-extrabold text-slate-900">Tren Pengeluaran</h3>
            <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-sky-50 text-sky-700 ring-1 ring-sky-200">Line</span>
        </div>
        <div class="p-6">
            <canvas id="spendingLineChart" height="110"></canvas>
        </div>
    </div>

    {{-- Doughnut Chart: Distribusi Status --}}
    <div class="rounded-2xl border border-sky-100 bg-white/80 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-sky-100 flex items-center justify-between">
            <h3 class="text-lg font-extrabold text-slate-900">Distribusi Status</h3>
            <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-sky-50 text-sky-700 ring-1 ring-sky-200">Doughnut</span>
        </div>
        <div class="p-6">
            <canvas id="statusDonutChart" height="220"></canvas>
        </div>
    </div>
</div>

{{-- ======= RIWAYAT PEMBAYARAN ======= --}}
<div class="rounded-2xl border border-sky-100 bg-white/80 shadow-sm overflow-hidden">
    <div class="p-6 border-b border-sky-100 flex items-center justify-between">
        <h3 class="text-xl font-extrabold text-slate-900">Riwayat Pembayaran</h3>
    </div>

    <div class="divide-y divide-sky-100">
        @forelse($payments as $payment)
        <div class="p-6 flex flex-col md:flex-row justify-between md:items-center hover:bg-sky-50/40 transition-colors">
            <div class="flex-1 mb-4 md:mb-0">
                <div class="flex items-center gap-3 mb-2">
                    <span class="text-lg font-extrabold text-slate-900">Booking #{{ $payment->booking_id }}</span>
                    <span class="px-3 py-1 text-xs font-bold rounded-full ring-1 whitespace-nowrap
              @if($payment->status=='pending_verification') bg-amber-50 text-amber-700 ring-amber-200
              @elseif($payment->status=='paid') bg-emerald-50 text-emerald-700 ring-emerald-200
              @elseif($payment->status=='cancelled' || $payment->status=='failed') bg-rose-50 text-rose-700 ring-rose-200
              @else bg-slate-50 text-slate-700 ring-slate-200 @endif">
                        {{ str_replace('_', ' ', ucfirst($payment->status)) }}
                    </span>
                </div>

                <p class="text-sm text-slate-600">
                    @if($payment->booking)
                    {{ $payment->booking->services->pluck('name')->join(', ') }}
                    @else
                    Layanan tidak ditemukan
                    @endif
                </p>
                <p class="text-sm text-slate-500 mt-1">{{ \Carbon\Carbon::parse($payment->created_at)->format('d F Y, H:i') }}</p>
            </div>

            <div class="flex-shrink-0 md:mx-6 md:text-right">
                <p class="text-lg font-extrabold bg-clip-text text-transparent"
                    style="background-image:linear-gradient(90deg,#2563eb,#06b6d4);">
                    Rp {{ number_format($payment->amount, 0, ',', '.') }}
                </p>
                <p class="text-sm text-slate-600 capitalize">{{ str_replace('_', ' ', $payment->payment_type) }}</p>
            </div>

            <div class="flex-shrink-0 mt-4 md:mt-0">
                <a href="{{ route('user.payments.show', $payment->id) }}"
                    class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl font-semibold text-white shadow-sm hover:shadow-md transition"
                    style="background:linear-gradient(90deg,#3b82f6,#06b6d4);">
                    <i class="fas fa-eye"></i>
                    Lihat Detail
                </a>
            </div>
        </div>
        @empty
        <div class="text-center p-12">
            <i class="fas fa-file-invoice-dollar text-slate-300 text-6xl mb-4"></i>
            <h3 class="text-xl font-extrabold text-slate-900">Belum Ada Riwayat Pembayaran</h3>
            <p class="text-slate-500 mt-2">Semua riwayat transaksi Anda akan muncul di sini.</p>
        </div>
        @endforelse
    </div>

    @if(method_exists($payments,'hasPages') && $payments->hasPages())
    <div class="p-4 border-t border-sky-100 bg-white/70">
        {{ $payments->links() }}
    </div>
    @endif
</div>

{{-- Inject data untuk Chart.js --}}
<script>
    window.__PAYMENTS_DATA__ = @json($chartPayload);
</script>
@endsection

@push('scripts')
{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    (function() {
        const data = Array.isArray(window.__PAYMENTS_DATA__) ? window.__PAYMENTS_DATA__ : [];

        // ==== Helper: format bulan ====
        function monthKey(d) {
            const dt = new Date(d);
            return `${dt.getFullYear()}-${String(dt.getMonth()+1).padStart(2,'0')}`; // 2025-03
        }

        function monthLabel(key) {
            const [y, m] = key.split('-').map(Number);
            return new Date(y, m - 1, 1).toLocaleDateString('id-ID', {
                month: 'short',
                year: 'numeric'
            });
        }

        // ==== Aggregate per bulan (amount untuk status "paid" saja, agar lebih representatif) ====
        const byMonth = {};
        data.forEach(p => {
            const key = monthKey(p.created_at);
            const include = (p.status === 'paid'); // hanya paid untuk tren pengeluaran
            if (!include) return;
            byMonth[key] = (byMonth[key] || 0) + (Number(p.amount) || 0);
        });
        const monthKeys = Object.keys(byMonth).sort();
        const monthLabels = monthKeys.map(monthLabel);
        const monthValues = monthKeys.map(k => byMonth[k]);

        // ==== Distribusi status (dari semua data halaman) ====
        const statusCount = {};
        data.forEach(p => {
            statusCount[p.status] = (statusCount[p.status] || 0) + 1;
        });
        const statusOrder = ['paid', 'pending_verification', 'cancelled', 'failed']; // urutan tampilan
        const statusLabels = [];
        const statusValues = [];
        statusOrder.forEach(s => {
            if (statusCount[s]) {
                statusLabels.push(s.replaceAll('_', ' '));
                statusValues.push(statusCount[s]);
            }
        });
        // masukkan status lain (jika ada)
        Object.keys(statusCount).forEach(s => {
            if (!statusOrder.includes(s)) {
                statusLabels.push(s.replaceAll('_', ' '));
                statusValues.push(statusCount[s]);
            }
        });

        // ==== Build Line Chart ====
        const lineCtx = document.getElementById('spendingLineChart')?.getContext('2d');
        if (lineCtx) {
            const gradient = lineCtx.createLinearGradient(0, 0, 0, 260);
            gradient.addColorStop(0, 'rgba(59,130,246,0.25)'); // sky-ish
            gradient.addColorStop(1, 'rgba(59,130,246,0.02)');

            new Chart(lineCtx, {
                type: 'line',
                data: {
                    labels: monthLabels,
                    datasets: [{
                        label: 'Pengeluaran (Paid)',
                        data: monthValues,
                        fill: true,
                        backgroundColor: gradient,
                        borderColor: '#3b82f6',
                        borderWidth: 2,
                        pointRadius: 3,
                        pointHoverRadius: 5,
                        tension: 0.35,
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: (ctx) => {
                                    const val = ctx.parsed.y || 0;
                                    return ' Rp ' + val.toLocaleString('id-ID');
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#334155'
                            }
                        },
                        y: {
                            grid: {
                                color: 'rgba(2,132,199,0.08)'
                            },
                            ticks: {
                                color: '#334155',
                                callback: (v) => 'Rp ' + Number(v).toLocaleString('id-ID')
                            }
                        }
                    }
                }
            });
        }

        // ==== Build Doughnut Chart ====
        const donutCtx = document.getElementById('statusDonutChart')?.getContext('2d');
        if (donutCtx) {
            new Chart(donutCtx, {
                type: 'doughnut',
                data: {
                    labels: statusLabels.map(s => s.charAt(0).toUpperCase() + s.slice(1)),
                    datasets: [{
                        data: statusValues,
                        backgroundColor: [
                            '#10b981', // paid
                            '#f59e0b', // pending verification
                            '#ef4444', // cancelled
                            '#f87171', // failed
                            '#6366f1', '#06b6d4', '#3b82f6'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    cutout: '65%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 10,
                                color: '#334155'
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: (ctx) => `${ctx.label}: ${ctx.parsed} transaksi`
                            }
                        }
                    }
                }
            });
        }
    })();
</script>
@endpush