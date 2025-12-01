@extends('layouts.user')

@section('title', 'User Dashboard - ServisAC')
@section('header-title', 'Dashboard')

@section('content')
{{-- ===== Stats (glass + gradient) ===== --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 lg:gap-6 mb-6">
    <div class="group relative rounded-2xl p-6 text-slate-900 bg-white/80 backdrop-blur border border-sky-100 shadow-sm hover:shadow-lg transition">
        <div class="absolute inset-0 rounded-2xl pointer-events-none"
            style="background:linear-gradient(90deg,#e8f2ff,#e6fbff); opacity:.6"></div>
        <div class="relative flex items-center gap-4">
            <div class="h-11 w-11 rounded-xl grid place-items-center text-sky-700"
                style="background:linear-gradient(135deg,#dff4ff,#bde7ff); box-shadow:inset 0 0 0 2px #fff;">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div>
                <p class="text-xs font-semibold tracking-wide text-sky-600/90 uppercase">Total Booking</p>
                <p class="text-2xl font-extrabold">{{ $userStats['total_bookings'] }}</p>
            </div>
        </div>
    </div>

    <div class="group relative rounded-2xl p-6 text-slate-900 bg-white/80 backdrop-blur border border-amber-100 shadow-sm hover:shadow-lg transition">
        <div class="absolute inset-0 rounded-2xl pointer-events-none"
            style="background:linear-gradient(90deg,#fff6e6,#fff1cc); opacity:.6"></div>
        <div class="relative flex items-center gap-4">
            <div class="h-11 w-11 rounded-xl grid place-items-center text-amber-700"
                style="background:linear-gradient(135deg,#fff1cc,#ffe7a3); box-shadow:inset 0 0 0 2px #fff;">
                <i class="fas fa-clock"></i>
            </div>
            <div>
                <p class="text-xs font-semibold tracking-wide text-amber-700/90 uppercase">Booking Pending</p>
                <p class="text-2xl font-extrabold">{{ $userStats['pending_bookings'] }}</p>
            </div>
        </div>
    </div>

    <div class="group relative rounded-2xl p-6 text-slate-900 bg-white/80 backdrop-blur border border-emerald-100 shadow-sm hover:shadow-lg transition">
        <div class="absolute inset-0 rounded-2xl pointer-events-none"
            style="background:linear-gradient(90deg,#e9fff4,#d7ffe9); opacity:.6"></div>
        <div class="relative flex items-center gap-4">
            <div class="h-11 w-11 rounded-xl grid place-items-center text-emerald-700"
                style="background:linear-gradient(135deg,#d9fbe8,#bff5d7); box-shadow:inset 0 0 0 2px #fff;">
                <i class="fas fa-check-circle"></i>
            </div>
            <div>
                <p class="text-xs font-semibold tracking-wide text-emerald-700/90 uppercase">Booking Selesai</p>
                <p class="text-2xl font-extrabold">{{ $userStats['completed_bookings'] }}</p>
            </div>
        </div>
    </div>

    <div class="group relative rounded-2xl p-6 text-slate-900 bg-white/80 backdrop-blur border border-indigo-100 shadow-sm hover:shadow-lg transition">
        <div class="absolute inset-0 rounded-2xl pointer-events-none"
            style="background:linear-gradient(90deg,#eef0ff,#e1e6ff); opacity:.65"></div>
        <div class="relative flex items-center gap-4">
            <div class="h-11 w-11 rounded-xl grid place-items-center text-indigo-700"
                style="background:linear-gradient(135deg,#e2e7ff,#ccd6ff); box-shadow:inset 0 0 0 2px #fff;">
                <i class="fas fa-wallet"></i>
            </div>
            <div>
                <p class="text-xs font-semibold tracking-wide text-indigo-700/90 uppercase">Total Pengeluaran</p>
                <p class="text-2xl font-extrabold">Rp {{ number_format($userStats['total_spent'], 0, ',', '.') }}</p>
            </div>
        </div>
    </div>
</div>

{{-- ===== 2 Column: Aksi Cepat + Booking Terbaru ===== --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Aksi Cepat --}}
    <div class="lg:col-span-1 bg-white/85 backdrop-blur border border-sky-100 rounded-2xl shadow-sm p-6">
        <div class="flex items-center gap-3 mb-4">
            <div class="h-9 w-9 rounded-xl grid place-items-center text-sky-700"
                style="background:linear-gradient(135deg,#dff4ff,#bde7ff); box-shadow:inset 0 0 0 2px #fff;">
                <i class="fas fa-bolt"></i>
            </div>
            <h3 class="text-xl font-extrabold bg-clip-text text-transparent"
                style="background-image:linear-gradient(90deg,#2563eb,#06b6d4);">Aksi Cepat</h3>
        </div>

        <div class="space-y-3">
            <a href="{{ route('user.bookings.create') }}"
                class="block w-full text-center px-4 py-3 rounded-xl font-semibold text-white shadow-md transition
                hover:shadow-lg"
                style="background:linear-gradient(90deg,#3b82f6,#06b6d4);">
                <i class="fas fa-plus mr-2"></i>Booking Baru
            </a>

            <a href="{{ route('user.payments') }}"
                class="block w-full text-center px-4 py-3 rounded-xl font-semibold text-slate-700 bg-white
                border border-sky-100 hover:border-sky-200 hover:bg-sky-50/50 transition">
                <i class="fas fa-wallet mr-2"></i>Pembayaran Saya
            </a>

            <a href="{{ route('user.profile') }}"
                class="block w-full text-center px-4 py-3 rounded-xl font-semibold text-slate-700 bg-white
                border border-sky-100 hover:border-sky-200 hover:bg-sky-50/50 transition">
                <i class="fas fa-user-edit mr-2"></i>Ubah Profil
            </a>
        </div>
    </div>

    {{-- Booking Terbaru --}}
    <div class="lg:col-span-2 bg-white/85 backdrop-blur border border-sky-100 rounded-2xl shadow-sm p-6">
        <div class="flex items-center gap-3 mb-4">
            <div class="h-9 w-9 rounded-xl grid place-items-center text-indigo-700"
                style="background:linear-gradient(135deg,#e2e7ff,#ccd6ff); box-shadow:inset 0 0 0 2px #fff;">
                <i class="fas fa-list-check"></i>
            </div>
            <h3 class="text-xl font-extrabold text-slate-800">Booking Terbaru</h3>
        </div>

        <div class="space-y-4">
            @forelse($recent_bookings as $booking)
            <div class="flex items-center justify-between p-4 rounded-xl border border-sky-100 bg-white hover:bg-sky-50/50 hover:shadow-sm transition">
                <div class="flex items-start gap-3">
                    <div class="h-10 w-10 rounded-lg grid place-items-center text-sky-700"
                        style="background:linear-gradient(135deg,#dff4ff,#bde7ff); box-shadow:inset 0 0 0 2px #fff;">
                        <i class="fas fa-snowflake"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-slate-900">
                            @foreach($booking->services as $service)
                            {{ $service->name }}@if(!$loop->last), @endif
                            @endforeach
                        </p>
                        <p class="text-sm text-slate-500">{{ $booking->booking_date->format('d M Y, H:i') }}</p>
                    </div>
                </div>

                <span class="px-3 py-1 rounded-full text-xs font-semibold ring-1
            @if($booking->status == 'pending') bg-amber-50 text-amber-700 ring-amber-200
            @elseif($booking->status == 'completed') bg-emerald-50 text-emerald-700 ring-emerald-200
            @elseif($booking->status == 'cancelled') bg-rose-50 text-rose-700 ring-rose-200
            @elseif($booking->status == 'assigned') bg-indigo-50 text-indigo-700 ring-indigo-200
            @elseif($booking->status == 'confirmed') bg-sky-50 text-sky-700 ring-sky-200
            @else bg-slate-50 text-slate-700 ring-slate-200 @endif">
                    {{ str_replace('_', ' ', ucfirst($booking->status)) }}
                </span>
            </div>
            @empty
            <div class="text-center py-10 rounded-2xl border border-sky-100 bg-white/70">
                <div class="h-14 w-14 mx-auto mb-3 rounded-2xl grid place-items-center text-slate-400"
                    style="background:linear-gradient(135deg,#f3f8ff,#eef6ff);">
                    <i class="fas fa-folder-open text-2xl"></i>
                </div>
                <p class="text-slate-500">Belum ada booking terbaru</p>
            </div>
            @endforelse
        </div>

        @if($recent_bookings->count() > 0)
        <div class="mt-6 text-center">
            <a href="{{ route('user.bookings') }}"
                class="inline-flex items-center gap-2 font-semibold bg-clip-text text-transparent hover:opacity-90"
                style="background-image:linear-gradient(90deg,#2563eb,#06b6d4);">
                Lihat Semua Booking
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        @endif
    </div>
</div>
@endsection