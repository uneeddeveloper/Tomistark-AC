@extends('layouts.user')

@section('title', 'Profil Saya - ServisAC')
@section('header-title', 'Profil Saya')

@section('content')
<div class="max-w-5xl mx-auto">

    {{-- ===== Kartu: Informasi Pribadi ===== --}}
    <form action="{{ route('user.profile.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="rounded-2xl bg-white/85 backdrop-blur border border-sky-100 shadow-sm overflow-hidden mb-6">
            <div class="px-6 py-5 border-b border-sky-100 flex items-center gap-3">
                <div class="h-10 w-10 rounded-xl grid place-items-center text-sky-700"
                    style="background:linear-gradient(135deg,#dff4ff,#bde7ff); box-shadow:inset 0 0 0 2px #fff;">
                    <i class="fas fa-id-card"></i>
                </div>
                <div>
                    <h2 class="text-xl font-extrabold text-slate-900">Informasi Pribadi</h2>
                    <p class="text-sm text-slate-500">Perbarui data personal dan alamat Anda.</p>
                </div>
            </div>

            <div class="p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Nama --}}
                    <div>
                        <label for="name" class="block text-xs font-bold tracking-wide text-slate-700 mb-2 uppercase">Nama Lengkap *</label>
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 group-focus-within:text-sky-600">
                                <i class="fas fa-user"></i>
                            </span>
                            <input
                                type="text" id="name" name="name"
                                value="{{ old('name', $user->name) }}"
                                class="w-full pl-10 pr-3 py-2.5 rounded-xl border border-sky-100 bg-white text-slate-900
                       focus:outline-none focus:ring-4 focus:ring-sky-200/60 focus:border-sky-300 transition"
                                required>
                        </div>
                        @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-xs font-bold tracking-wide text-slate-700 mb-2 uppercase">Alamat Email *</label>
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 group-focus-within:text-sky-600">
                                <i class="fas fa-envelope"></i>
                            </span>

                            {{-- tetap menghormati google_id --}}
                            <input
                                type="email" id="email" name="email" value="{{ $user->email }}"
                                class="w-full pl-10 pr-3 py-2.5 rounded-xl border border-sky-100 bg-white text-slate-900
                       focus:outline-none focus:ring-4 focus:ring-sky-200/60 focus:border-sky-300 transition
                       {{ $user->google_id ? 'bg-slate-100 cursor-not-allowed' : '' }}"
                                {{ $user->google_id ? 'readonly' : '' }} required>
                        </div>
                        @if($user->google_id)
                        <p class="text-xs text-slate-500 mt-1">Email tidak dapat diubah (Login via Google).</p>
                        @endif
                        @error('email') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- WhatsApp --}}
                <div>
                    <label for="phone_number" class="block text-xs font-bold tracking-wide text-slate-700 mb-2 uppercase">
                        Nomor WhatsApp (Wajib) *
                    </label>
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 group-focus-within:text-emerald-600">
                            <i class="fab fa-whatsapp"></i>
                        </span>
                        <input
                            type="text" id="phone_number" name="phone_number"
                            value="{{ old('phone_number', $user->phone_number) }}"
                            placeholder="cth: 08123456789"
                            class="w-full pl-10 pr-3 py-2.5 rounded-xl border border-sky-100 bg-white text-slate-900
                     focus:outline-none focus:ring-4 focus:ring-sky-200/60 focus:border-sky-300 transition"
                            required>
                    </div>
                    <p class="text-xs text-slate-500 mt-1">Pastikan nomor aktif untuk koordinasi teknisi & notifikasi.</p>
                    @error('phone_number') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Alamat --}}
                <div>
                    <label for="address" class="block text-xs font-bold tracking-wide text-slate-700 mb-2 uppercase">Alamat</label>
                    <div class="relative group">
                        <span class="absolute top-3.5 left-0 flex items-center pl-3 text-slate-400 group-focus-within:text-sky-600">
                            <i class="fas fa-map-marker-alt"></i>
                        </span>
                        <textarea
                            id="address" name="address" rows="3"
                            placeholder="Masukkan alamat lengkap Anda..."
                            class="w-full pl-10 pr-3 py-2.5 rounded-xl border border-sky-100 bg-white text-slate-900
                     focus:outline-none focus:ring-4 focus:ring-sky-200/60 focus:border-sky-300 transition">{{ old('address', $user->address) }}</textarea>
                    </div>
                    @error('address') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- ===== Kartu: Keamanan Akun ===== --}}
        <div class="rounded-2xl bg-white/85 backdrop-blur border border-sky-100 shadow-sm overflow-hidden mb-6">
            <div class="px-6 py-5 border-b border-sky-100 flex items-center gap-3">
                <div class="h-10 w-10 rounded-xl grid place-items-center text-indigo-700"
                    style="background:linear-gradient(135deg,#e2e7ff,#ccd6ff); box-shadow:inset 0 0 0 2px #fff;">
                    <i class="fas fa-shield-halved"></i>
                </div>
                <div>
                    <h2 class="text-xl font-extrabold text-slate-900">Keamanan Akun</h2>
                    <p class="text-sm text-slate-500">Ubah kata sandi. Biarkan kosong jika tidak ingin mengubah.</p>
                </div>
            </div>

            <div class="p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Password baru --}}
                    <div>
                        <label for="password" class="block text-xs font-bold tracking-wide text-slate-700 mb-2 uppercase">Kata Sandi Baru</label>
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 group-focus-within:text-sky-600">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input
                                type="password" id="password" name="password"
                                class="w-full pl-10 pr-10 py-2.5 rounded-xl border border-sky-100 bg-white text-slate-900
                       focus:outline-none focus:ring-4 focus:ring-sky-200/60 focus:border-sky-300 transition">
                            <button type="button" data-toggle="#password"
                                class="absolute inset-y-0 right-0 pr-3 text-slate-400 hover:text-slate-600">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                        @error('password') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Konfirmasi --}}
                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold tracking-wide text-slate-700 mb-2 uppercase">
                            Konfirmasi Kata Sandi Baru
                        </label>
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 group-focus-within:text-sky-600">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input
                                type="password" id="password_confirmation" name="password_confirmation"
                                class="w-full pl-10 pr-10 py-2.5 rounded-xl border border-sky-100 bg-white text-slate-900
                       focus:outline-none focus:ring-4 focus:ring-sky-200/60 focus:border-sky-300 transition">
                            <button type="button" data-toggle="#password_confirmation"
                                class="absolute inset-y-0 right-0 pr-3 text-slate-400 hover:text-slate-600">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 bg-sky-50/60 border-t border-sky-100 flex justify-end">
                <button type="submit"
                    class="px-6 py-2.5 rounded-xl font-semibold text-white shadow-md hover:shadow-lg transition
                 focus:outline-none focus:ring-4 focus:ring-sky-200/70"
                    style="background:linear-gradient(90deg,#3b82f6,#06b6d4);">
                    Perbarui Profil
                </button>
            </div>
        </div>
    </form>

    {{-- ===== Kartu: Informasi Akun ===== --}}
    <div class="rounded-2xl bg-white/85 backdrop-blur border border-sky-100 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-sky-100 flex items-center gap-3">
            <div class="h-9 w-9 rounded-xl grid place-items-center text-cyan-700"
                style="background:linear-gradient(135deg,#dff7ff,#bfe8ff); box-shadow:inset 0 0 0 2px #fff;">
                <i class="fas fa-circle-info"></i>
            </div>
            <h3 class="text-xl font-extrabold text-slate-900">Informasi Akun</h3>
        </div>

        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
            {{-- Bergabung sejak --}}
            <div class="flex items-center p-4 rounded-xl border border-sky-100 bg-white">
                <div class="h-11 w-11 rounded-xl grid place-items-center text-sky-700 mr-4"
                    style="background:linear-gradient(135deg,#e8f2ff,#d6eaff); box-shadow:inset 0 0 0 2px #fff;">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div>
                    <p class="text-sm text-slate-500">Bergabung Sejak</p>
                    <p class="font-semibold text-slate-900">{{ $user->created_at->format('d F Y') }}</p>
                </div>
            </div>

            {{-- Status email --}}
            <div class="flex items-center p-4 rounded-xl border border-sky-100 bg-white">
                <div class="h-11 w-11 rounded-xl grid place-items-center mr-4
                    @if($user->email_verified_at) text-emerald-700
                    @else text-amber-700 @endif"
                    style="background:linear-gradient(135deg,#f1fff6,#ddffe9); box-shadow:inset 0 0 0 2px #fff;">
                    @if($user->email_verified_at)
                    <i class="fas fa-check-circle"></i>
                    @else
                    <i class="fas fa-exclamation-triangle"></i>
                    @endif
                </div>
                <div>
                    <p class="text-sm text-slate-500">Status Email</p>
                    <p class="font-semibold">
                        @if($user->email_verified_at)
                        <span class="text-emerald-700">Terverifikasi</span>
                        @else
                        <span class="text-amber-700">Belum Terverifikasi</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Toggle show/hide password (UI only, tidak mengubah backend) --}}
@push('scripts')
<script>
    document.querySelectorAll('[data-toggle]').forEach(btn => {
        btn.addEventListener('click', () => {
            const sel = btn.getAttribute('data-toggle');
            const input = document.querySelector(sel);
            if (!input) return;
            input.type = input.type === 'password' ? 'text' : 'password';
            const icon = btn.querySelector('i');
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        });
    });
</script>
@endpush
@endsection