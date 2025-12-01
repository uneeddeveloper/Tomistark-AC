@extends('admin.layout')

@section('title', 'User Details - ServisAC')

{{-- Header atas --}}
@section('header', 'User Details')
@section('subheader', 'Detail akun & informasi kontak pengguna.')
@section('header-actions')
<a href="{{ route('admin.users.index') }}"
    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-slate-200 text-slate-700 hover:bg-slate-50">
    <i class="fas fa-arrow-left"></i> Kembali ke List
</a>
<a href="{{ route('admin.users.edit', $user->id) }}"
    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-600 text-white shadow-sm hover:bg-emerald-700">
    <i class="fas fa-user-edit"></i> Edit User
</a>
@endsection

@push('styles')
<style>
    .card {
        background: #fff;
        border: 1px solid rgba(14, 165, 233, .15);
        border-radius: 20px;
        box-shadow: 0 10px 28px rgba(2, 132, 199, .08);
    }

    .lift {
        transition: transform .15s ease, box-shadow .15s ease;
    }

    .lift:hover {
        transform: translateY(-2px);
        box-shadow: 0 14px 32px rgba(2, 132, 199, .12);
    }

    .chip {
        padding: .35rem .7rem;
        border-radius: 999px;
        font-size: .75rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: .4rem;
    }

    .info-item {
        display: flex;
        align-items: flex-start;
        gap: .75rem;
        padding: .75rem 1rem;
        border: 1px solid rgba(15, 23, 42, .06);
        border-radius: 14px;
        background: #f8fbff;
    }
</style>
@endpush

@section('content')
<div class="-mx-6 -mt-6 p-6 lg:-mx-10 lg:-mt-10 lg:p-10 rounded-3xl"
    style="background:linear-gradient(180deg,#f2f9ff 0%,#f6fbff 55%,#f8fcff 100%);">

    {{-- Kartu header user --}}
    <div class="card lift p-6 mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-sky-500 to-indigo-500 text-white grid place-items-center text-2xl font-bold">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                    <h1 class="text-2xl font-extrabold text-slate-900 leading-tight">{{ $user->name }}</h1>
                    <p class="text-slate-600 text-sm">{{ $user->email }}</p>
                    <div class="mt-2 flex flex-wrap items-center gap-2">
                        <span class="chip
              @if($user->role=='admin') bg-purple-50 text-purple-700
              @elseif($user->role=='technician') bg-indigo-50 text-indigo-700
              @else bg-sky-50 text-sky-700 @endif">
                            <i class="fas fa-id-badge text-[11px]"></i>{{ ucfirst($user->role) }}
                        </span>
                        @if($user->email_verified_at)
                        <span class="chip bg-emerald-50 text-emerald-700">
                            <i class="fas fa-check-circle text-[11px]"></i> Verified
                        </span>
                        @else
                        <span class="chip bg-amber-50 text-amber-700">
                            <i class="fas fa-exclamation-circle text-[11px]"></i> Not Verified
                        </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('admin.users.index') }}"
                    class="hidden sm:inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-slate-200 text-slate-700 hover:bg-slate-50">
                    <i class="fas fa-list"></i> List Pengguna
                </a>
                <a href="{{ route('admin.users.edit', $user->id) }}"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-600 text-white hover:bg-emerald-700">
                    <i class="fas fa-user-edit"></i> Edit User
                </a>
            </div>
        </div>
    </div>

    {{-- Detail informasi --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Kolom kiri: Info utama --}}
        <div class="lg:col-span-2 card lift p-6">
            <h3 class="text-lg font-bold text-slate-900 mb-4">Informasi Pengguna</h3>

            <div class="grid sm:grid-cols-2 gap-4">
                <div class="info-item">
                    <i class="fas fa-user text-sky-500 mt-0.5"></i>
                    <div>
                        <p class="text-xs uppercase tracking-wide text-slate-500">Full Name</p>
                        <p class="font-semibold text-slate-900">{{ $user->name }}</p>
                    </div>
                </div>
                <div class="info-item">
                    <i class="fas fa-envelope text-sky-500 mt-0.5"></i>
                    <div>
                        <p class="text-xs uppercase tracking-wide text-slate-500">Email Address</p>
                        <p class="font-semibold text-slate-900 break-all">{{ $user->email }}</p>
                    </div>
                </div>
                <div class="info-item">
                    <i class="fab fa-whatsapp text-sky-500 mt-0.5"></i>
                    <div>
                        <p class="text-xs uppercase tracking-wide text-slate-500">Phone Number</p>
                        <p class="font-semibold text-slate-900">{{ $user->phone_number ?? 'N/A' }}</p>
                    </div>
                </div>
                <div class="info-item">
                    <i class="fas fa-user-shield text-sky-500 mt-0.5"></i>
                    <div>
                        <p class="text-xs uppercase tracking-wide text-slate-500">Role</p>
                        <p class="font-semibold text-slate-900">{{ ucfirst($user->role) }}</p>
                    </div>
                </div>
                <div class="info-item sm:col-span-2">
                    <i class="fas fa-map-marker-alt text-sky-500 mt-0.5"></i>
                    <div>
                        <p class="text-xs uppercase tracking-wide text-slate-500">Address</p>
                        <p class="font-semibold text-slate-900">{{ $user->address ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Kolom kanan: Meta akun --}}
        <div class="card lift p-6">
            <h3 class="text-lg font-bold text-slate-900 mb-4">Ringkasan Akun</h3>
            <div class="space-y-3">
                <div class="info-item">
                    <i class="fas fa-calendar-check text-sky-500 mt-0.5"></i>
                    <div>
                        <p class="text-xs uppercase tracking-wide text-slate-500">Member Since</p>
                        <p class="font-semibold text-slate-900">{{ $user->created_at->format('F d, Y') }}</p>
                    </div>
                </div>
                <div class="info-item">
                    <i class="fas fa-shield-alt text-sky-500 mt-0.5"></i>
                    <div>
                        <p class="text-xs uppercase tracking-wide text-slate-500">Email Status</p>
                        @if($user->email_verified_at)
                        <p class="font-semibold text-emerald-700">Verified</p>
                        @else
                        <p class="font-semibold text-rose-700">Not Verified</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="mt-6 flex flex-col sm:flex-row gap-3">
                <a href="{{ route('admin.users.index') }}"
                    class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 w-full sm:w-auto">
                    <i class="fas fa-arrow-left"></i> Kembali ke List
                </a>
                <a href="{{ route('admin.users.edit', $user->id) }}"
                    class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl bg-emerald-600 text-white hover:bg-emerald-700 w-full sm:w-auto">
                    <i class="fas fa-user-edit"></i> Edit User
                </a>
            </div>
        </div>
    </div>
</div>
@endsection