@extends('layouts.user')

@section('title', 'Booking Saya - ServisAC')
@section('header-title', 'Booking Saya')

@section('content')

{{-- ===== Header Halaman ===== --}}
<div class="mb-6 flex flex-col md:flex-row justify-between md:items-center gap-3">
    <div>
        <h2 class="text-2xl font-extrabold bg-clip-text text-transparent"
            style="background-image:linear-gradient(90deg,#2563eb,#06b6d4);">
            Riwayat Booking Saya
        </h2>
        <p class="text-slate-600">Lihat semua status booking Anda di sini.</p>
    </div>

    <a href="{{ route('user.bookings.create') }}"
        class="w-full md:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl font-semibold text-white shadow-md hover:shadow-lg transition"
        style="background:linear-gradient(90deg,#3b82f6,#06b6d4);">
        <i class="fas fa-plus"></i> Buat Booking Baru
    </a>
</div>

{{-- ===== Filter Status ===== --}}
<div class="mb-6 rounded-2xl bg-white/85 backdrop-blur border border-sky-100 shadow-sm p-4">
    <div class="flex flex-wrap items-center gap-2 md:gap-3">
        <span class="text-xs font-bold tracking-wide text-slate-700 uppercase mr-2">Filter Status:</span>

        <a href="{{ route('user.bookings') }}"
            class="px-3 py-1.5 rounded-full text-sm font-semibold transition
              {{ !request('status') 
                  ? 'text-white shadow hover:opacity-95' 
                  : 'bg-white text-slate-700 ring-1 ring-sky-200 hover:bg-sky-50/60' }}"
            style="{{ !request('status') ? 'background:linear-gradient(90deg,#3b82f6,#06b6d4);' : '' }}">
            Semua
        </a>

        <a href="{{ route('user.bookings', ['status' => 'pending']) }}"
            class="px-3 py-1.5 rounded-full text-sm font-semibold transition
              {{ request('status')=='pending'
                  ? 'bg-amber-500 text-white shadow hover:opacity-95'
                  : 'bg-white text-slate-700 ring-1 ring-amber-200 hover:bg-amber-50/60' }}">
            Pending
        </a>

        <a href="{{ route('user.bookings', ['status' => 'confirmed']) }}"
            class="px-3 py-1.5 rounded-full text-sm font-semibold transition
              {{ request('status')=='confirmed'
                  ? 'text-white shadow hover:opacity-95'
                  : 'bg-white text-slate-700 ring-1 ring-sky-200 hover:bg-sky-50/60' }}"
            style="{{ request('status')=='confirmed' ? 'background:linear-gradient(90deg,#3b82f6,#06b6d4);' : '' }}">
            Dikonfirmasi
        </a>

        <a href="{{ route('user.bookings', ['status' => 'assigned']) }}"
            class="px-3 py-1.5 rounded-full text-sm font-semibold transition
              {{ request('status')=='assigned'
                  ? 'bg-indigo-600 text-white shadow hover:opacity-95'
                  : 'bg-white text-slate-700 ring-1 ring-indigo-200 hover:bg-indigo-50/60' }}">
            Ditugaskan
        </a>

        <a href="{{ route('user.bookings', ['status' => 'in_progress']) }}"
            class="px-3 py-1.5 rounded-full text-sm font-semibold transition
              {{ request('status')=='in_progress'
                  ? 'bg-violet-600 text-white shadow hover:opacity-95'
                  : 'bg-white text-slate-700 ring-1 ring-violet-200 hover:bg-violet-50/60' }}">
            Proses
        </a>

        <a href="{{ route('user.bookings', ['status' => 'completed']) }}"
            class="px-3 py-1.5 rounded-full text-sm font-semibold transition
              {{ request('status')=='completed'
                  ? 'bg-emerald-600 text-white shadow hover:opacity-95'
                  : 'bg-white text-slate-700 ring-1 ring-emerald-200 hover:bg-emerald-50/60' }}">
            Selesai
        </a>

        <a href="{{ route('user.bookings', ['status' => 'cancelled']) }}"
            class="px-3 py-1.5 rounded-full text-sm font-semibold transition
              {{ request('status')=='cancelled'
                  ? 'bg-rose-600 text-white shadow hover:opacity-95'
                  : 'bg-white text-slate-700 ring-1 ring-rose-200 hover:bg-rose-50/60' }}">
            Dibatalkan
        </a>
    </div>
</div>

{{-- ===== Grid Booking ===== --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($bookings as $booking)
    <div class="group relative rounded-2xl bg-white/85 backdrop-blur border border-sky-100 shadow-sm hover:shadow-lg transition overflow-hidden flex flex-col">
        {{-- top accent line --}}
        <div class="absolute left-0 right-0 top-0 h-1"
            style="background:linear-gradient(90deg,#22d3ee,#2563eb)"></div>

        {{-- Header kartu --}}
        <div class="p-4 border-b border-sky-100 bg-white/60">
            <div class="flex items-start justify-between gap-2">
                <div>
                    <p class="text-xs text-slate-500">Booking
                        <span class="font-bold text-slate-900">#{{ $booking->id }}</span>
                    </p>
                    <p class="text-sm text-slate-600">
                        <i class="fas fa-calendar-alt fa-fw mr-1 text-sky-600"></i>
                        {{ $booking->booking_date->format('d F Y, H:i') }}
                    </p>
                </div>

                {{-- Badge status --}}
                <span class="px-3 py-1 rounded-full text-xs font-bold ring-1 whitespace-nowrap
            @if($booking->status=='pending' || $booking->status=='pending_verification') bg-amber-50 text-amber-700 ring-amber-200
            @elseif($booking->status=='confirmed') bg-sky-50 text-sky-700 ring-sky-200
            @elseif($booking->status=='assigned') bg-indigo-50 text-indigo-700 ring-indigo-200
            @elseif($booking->status=='in_progress') bg-violet-50 text-violet-700 ring-violet-200
            @elseif($booking->status=='completed') bg-emerald-50 text-emerald-700 ring-emerald-200
            @elseif($booking->status=='cancelled') bg-rose-50 text-rose-700 ring-rose-200
            @else bg-slate-50 text-slate-700 ring-slate-200 @endif">
                    {{ str_replace('_', ' ', ucfirst($booking->status)) }}
                </span>
            </div>
        </div>

        {{-- Body --}}
        <div class="p-5 flex-grow">
            <h4 class="text-sm font-extrabold tracking-wide text-slate-800 mb-2 uppercase">Layanan</h4>
            <ul class="space-y-1.5 text-sm text-slate-700 mb-4">
                @foreach($booking->services as $service)
                <li class="flex items-center gap-2">
                    <span class="inline-flex h-5 w-5 rounded-md items-center justify-center text-sky-700 text-xs"
                        style="background:linear-gradient(135deg,#dff4ff,#bde7ff); box-shadow:inset 0 0 0 2px #fff;">
                        {{ $loop->iteration }}
                    </span>
                    <span>{{ $service->name }} ({{ $service->pivot->quantity }}x)</span>
                </li>
                @endforeach
            </ul>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <p class="text-[11px] font-bold tracking-wide text-slate-500 uppercase">Teknisi</p>
                    <p class="text-sm font-semibold text-slate-900 flex items-center">
                        <i class="fas fa-user-gear fa-fw mr-2 text-slate-400"></i>
                        {{ $booking->technician->name ?? 'Belum Ditugaskan' }}
                    </p>
                </div>
                <div>
                    <p class="text-[11px] font-bold tracking-wide text-slate-500 uppercase">Total Harga</p>
                    <p class="text-lg font-extrabold bg-clip-text text-transparent"
                        style="background-image:linear-gradient(90deg,#2563eb,#06b6d4);">
                        Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Footer aksi --}}
        <div class="p-4 bg-white/70 border-t border-sky-100 flex justify-end items-center gap-2">
            @if(in_array($booking->status, ['pending', 'confirmed']))
            <form action="{{ route('user.bookings.cancel', $booking->id) }}" method="POST" class="inline"
                onsubmit="return confirm('Apakah Anda yakin ingin membatalkan booking ini?')">
                @csrf
                <button type="submit"
                    class="px-3 py-2 text-xs font-semibold rounded-lg text-rose-700 bg-rose-50 ring-1 ring-rose-200 hover:bg-rose-100 transition">
                    Batalkan
                </button>
            </form>
            @endif

            @if($booking->status == 'completed' && !$booking->rating)
            <a href="{{ route('user.bookings.show', $booking->id) }}#rating-form"
                class="px-3 py-2 text-xs font-semibold text-white rounded-lg shadow-sm hover:shadow transition"
                style="background:linear-gradient(90deg,#10b981,#059669);">
                <i class="fas fa-star mr-1"></i> Beri Rating
            </a>
            @endif

            <a href="{{ route('user.bookings.show', $booking->id) }}"
                class="px-3 py-2 text-xs font-semibold text-white rounded-lg shadow-sm hover:shadow transition"
                style="background:linear-gradient(90deg,#3b82f6,#06b6d4);">
                Lihat Detail
            </a>
        </div>
    </div>
    @empty
    {{-- Empty state --}}
    <div class="lg:col-span-3 text-center rounded-2xl bg-white/85 backdrop-blur border border-sky-100 shadow-sm p-12">
        <div class="h-16 w-16 mx-auto mb-4 rounded-2xl grid place-items-center text-slate-400"
            style="background:linear-gradient(135deg,#f3f8ff,#eef6ff);">
            <i class="fas fa-folder-open text-3xl"></i>
        </div>
        <h3 class="text-xl font-extrabold text-slate-900">Belum Ada Booking</h3>
        <p class="text-slate-500 mt-2 mb-6">Anda belum pernah melakukan booking. Mulai pesan layanan pertama Anda!</p>
        <a href="{{ route('user.bookings.create') }}"
            class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-semibold text-white shadow-md hover:shadow-lg transition"
            style="background:linear-gradient(90deg,#3b82f6,#06b6d4);">
            <i class="fas fa-plus"></i> Buat Booking Pertama Anda
        </a>
    </div>
    @endforelse
</div>

{{-- ===== Pagination (biarkan fungsi asli) ===== --}}
@if($bookings->hasPages())
<div class="mt-8">
    {{ $bookings->links() }}
</div>
@endif

@endsection