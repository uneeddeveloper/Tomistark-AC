@extends('admin.layout')

@section('title', 'Manajemen Booking - ServisAC')
@section('header', 'Manajemen Booking')
@section('subheader', 'Kelola semua booking yang masuk ke sistem')

@section('content')

{{-- ===== Toolbar Filter Status (tema biru) ===== --}}
<div class="mb-6 rounded-2xl border border-blue-100 bg-white/90 backdrop-blur-sm shadow-md">
    <div class="px-5 py-4 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="flex items-center gap-3">
            <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-blue-600 via-indigo-600 to-sky-600 text-white ring-1 ring-blue-400/30 shadow">
                <i class="fas fa-filter"></i>
            </span>
            <div>
                <p class="text-sm text-slate-500">Filter</p>
                <h3 class="text-lg font-semibold text-slate-800">Status Booking</h3>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-2 md:gap-3">
            <a href="{{ route('admin.bookings.index') }}"
                class="px-3 py-1.5 rounded-full text-xs md:text-sm font-semibold transition
                  {{ !request('status')
                      ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow'
                      : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                <i class="fas fa-list-ul mr-1.5"></i> Semua
            </a>

            <a href="{{ route('admin.bookings.index', ['status' => 'pending_verification']) }}"
                class="px-3 py-1.5 rounded-full text-xs md:text-sm font-semibold transition
                  {{ request('status') == 'pending_verification'
                      ? 'bg-yellow-500 text-white shadow'
                      : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                <i class="fas fa-shield-halved mr-1.5"></i> Verifikasi
            </a>

            <a href="{{ route('admin.bookings.index', ['status' => 'assigned']) }}"
                class="px-3 py-1.5 rounded-full text-xs md:text-sm font-semibold transition
                  {{ request('status') == 'assigned'
                      ? 'bg-indigo-500 text-white shadow'
                      : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                <i class="fas fa-user-cog mr-1.5"></i> Ditugaskan
            </a>

            <a href="{{ route('admin.bookings.index', ['status' => 'completed']) }}"
                class="px-3 py-1.5 rounded-full text-xs md:text-sm font-semibold transition
                  {{ request('status') == 'completed'
                      ? 'bg-green-600 text-white shadow'
                      : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                <i class="fas fa-check-circle mr-1.5"></i> Selesai
            </a>

            <a href="{{ route('admin.bookings.index', ['status' => 'cancelled']) }}"
                class="px-3 py-1.5 rounded-full text-xs md:text-sm font-semibold transition
                  {{ request('status') == 'cancelled'
                      ? 'bg-red-600 text-white shadow'
                      : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                <i class="fas fa-ban mr-1.5"></i> Dibatalkan
            </a>
        </div>
    </div>
</div>

{{-- ===== Tabel Desktop & Tablet ===== --}}
<div class="hidden md:block bg-white rounded-2xl shadow-lg border border-blue-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-blue-50 text-slate-700 sticky top-0 z-10 border-b border-blue-100">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">Pelanggan</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">Tgl. Booking</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">Layanan</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">Teknisi</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">Total Harga</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($bookings as $booking)
                <tr class="hover:bg-blue-50/40 transition-colors">
                    {{-- Pelanggan --}}
                    <td class="px-6 py-4 align-top">
                        <div class="flex items-start gap-3">
                            <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white flex items-center justify-center font-bold">
                                {{ strtoupper(substr($booking->user->name ?? 'U', 0, 1)) }}
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-slate-900">{{ $booking->user->name ?? 'User Dihapus' }}</div>
                                <div class="text-xs text-slate-500">{{ $booking->user->phone_number ?? '-' }}</div>
                            </div>
                        </div>
                    </td>

                    {{-- Tanggal Booking --}}
                    <td class="px-6 py-4 align-top whitespace-nowrap">
                        <div class="text-sm text-slate-800">{{ $booking->booking_date->format('d M Y') }}</div>
                        <div class="text-xs text-slate-500">{{ $booking->booking_date->format('H:i') }} WIB</div>
                    </td>

                    {{-- Layanan --}}
                    <td class="px-6 py-4 align-top">
                        <div class="flex flex-wrap gap-1.5 max-w-sm">
                            @foreach($booking->services as $service)
                            <span class="bg-sky-50 text-sky-700 border border-sky-200 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                {{ $service->name }} ({{ $service->pivot->quantity }})
                            </span>
                            @endforeach
                        </div>
                    </td>

                    {{-- Teknisi --}}
                    <td class="px-6 py-4 align-top text-sm">
                        @if($booking->technician)
                        <span class="inline-flex items-center gap-1 text-slate-800">
                            <i class="fas fa-user-cog text-blue-500"></i> {{ $booking->technician->name }}
                        </span>
                        @else
                        <span class="text-slate-400 italic">Belum Ditugaskan</span>
                        @endif
                    </td>

                    {{-- Status --}}
                    <td class="px-6 py-4 align-top">
                        <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full
                  @if($booking->status == 'pending' || $booking->status == 'pending_verification') bg-yellow-100 text-yellow-800
                  @elseif($booking->status == 'confirmed') bg-blue-100 text-blue-800
                  @elseif($booking->status == 'assigned') bg-indigo-100 text-indigo-800
                  @elseif($booking->status == 'in_progress') bg-purple-100 text-purple-800
                  @elseif($booking->status == 'completed') bg-green-100 text-green-800
                  @elseif($booking->status == 'cancelled') bg-red-100 text-red-800
                  @else bg-slate-100 text-slate-800 @endif">
                            {{ str_replace('_', ' ', ucfirst($booking->status)) }}
                        </span>
                    </td>

                    {{-- Total Harga --}}
                    <td class="px-6 py-4 align-top">
                        <div class="text-sm font-semibold text-slate-900">
                            Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                        </div>
                    </td>

                    {{-- Aksi --}}
                    <td class="px-6 py-4 align-top text-right">
                        <a href="{{ route('admin.bookings.show', $booking->id) }}"
                            class="inline-flex items-center justify-center rounded-lg bg-gradient-to-r from-blue-600 to-indigo-600
                          px-3 py-1.5 text-xs font-semibold text-white shadow hover:from-blue-700 hover:to-indigo-700 transition">
                            <i class="fas fa-eye mr-1.5"></i> Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                        <i class="fas fa-folder-open text-4xl text-slate-300 mb-3"></i>
                        <p>Data booking tidak ditemukan.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($bookings->hasPages())
    <div class="p-4 border-t border-blue-100 bg-blue-50/40">
        {{ $bookings->links() }}
    </div>
    @endif
</div>

{{-- ===== Kartu Mobile (<= md) ===== --}}
<div class="md:hidden space-y-4">
    @forelse($bookings as $booking)
    <div class="rounded-2xl border border-blue-100 bg-white/90 shadow hover:shadow-md transition">
        <div class="p-4 border-b border-slate-100 flex items-start gap-3">
            <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white flex items-center justify-center font-bold">
                {{ strtoupper(substr($booking->user->name ?? 'U', 0, 1)) }}
            </div>
            <div class="flex-1">
                <div class="flex items-center justify-between">
                    <h4 class="font-semibold text-slate-900">{{ $booking->user->name ?? 'User Dihapus' }}</h4>
                    <span class="px-2.5 py-0.5 text-[11px] font-semibold rounded-full
                @if($booking->status == 'pending' || $booking->status == 'pending_verification') bg-yellow-100 text-yellow-800
                @elseif($booking->status == 'confirmed') bg-blue-100 text-blue-800
                @elseif($booking->status == 'assigned') bg-indigo-100 text-indigo-800
                @elseif($booking->status == 'in_progress') bg-purple-100 text-purple-800
                @elseif($booking->status == 'completed') bg-green-100 text-green-800
                @elseif($booking->status == 'cancelled') bg-red-100 text-red-800
                @else bg-slate-100 text-slate-800 @endif">
                        {{ str_replace('_', ' ', ucfirst($booking->status)) }}
                    </span>
                </div>
                <p class="text-xs text-slate-500">{{ $booking->user->phone_number ?? '-' }}</p>
            </div>
        </div>

        <div class="p-4 space-y-3">
            <div class="text-sm">
                <p class="text-slate-500">Tanggal</p>
                <p class="font-medium text-slate-800">
                    {{ $booking->booking_date->format('d M Y') }} • {{ $booking->booking_date->format('H:i') }} WIB
                </p>
            </div>

            <div class="text-sm">
                <p class="text-slate-500 mb-1">Layanan</p>
                <div class="flex flex-wrap gap-1.5">
                    @foreach($booking->services as $service)
                    <span class="bg-sky-50 text-sky-700 border border-sky-200 text-[11px] font-medium px-2 py-0.5 rounded-full">
                        {{ $service->name }} ({{ $service->pivot->quantity }})
                    </span>
                    @endforeach
                </div>
            </div>

            <div class="text-sm">
                <p class="text-slate-500">Teknisi</p>
                <p class="font-medium text-slate-800">
                    @if($booking->technician) {{ $booking->technician->name }}
                    @else <span class="text-slate-400 italic">Belum Ditugaskan</span>
                    @endif
                </p>
            </div>

            <div class="flex items-center justify-between pt-2">
                <div class="text-sm font-semibold text-slate-900">
                    Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                </div>
                <a href="{{ route('admin.bookings.show', $booking->id) }}"
                    class="inline-flex items-center gap-1.5 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 px-3 py-2 text-xs font-semibold text-white shadow hover:from-blue-700 hover:to-indigo-700 transition">
                    <i class="fas fa-eye"></i> Detail
                </a>
            </div>
        </div>
    </div>
    @empty
    <div class="text-center bg-white rounded-2xl border border-blue-100 p-8 shadow">
        <i class="fas fa-folder-open text-4xl text-slate-300 mb-2"></i>
        <p class="text-slate-500">Data booking tidak ditemukan.</p>
    </div>
    @endforelse

    @if($bookings->hasPages())
    <div class="mt-4">
        {{ $bookings->links() }}
    </div>
    @endif
</div>

@endsection