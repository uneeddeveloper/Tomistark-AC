@extends('admin.layout')

@section('title', 'Add New User - ServisAC')

{{-- Header atas ikut layout --}}
@section('header', 'Add New User')
@section('subheader', 'Buat akun pengguna baru untuk tim atau pelanggan.')

@section('header-actions')
<a href="{{ route('admin.users.index') }}"
    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl
            bg-white border border-emerald-200 text-emerald-700
            hover:bg-emerald-50 hover:border-emerald-300 transition">
    <i class="fas fa-arrow-left"></i>
    Kembali ke List
</a>
@endsection

@section('content')
{{-- Banner tipis bertema login --}}
<div class="mb-6 rounded-2xl bg-gradient-to-r from-emerald-600 via-teal-600 to-sky-600 px-6 py-4 text-white shadow-md">
    <div class="flex items-center gap-3">
        <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-white/15 ring-1 ring-white/20">
            <i class="fas fa-user-plus text-white text-lg"></i>
        </span>
        <div>
            <p class="text-sm/5 text-emerald-50">Form Pendaftaran</p>
            <h2 class="text-xl font-semibold">Tambah Pengguna Baru</h2>
        </div>
    </div>
</div>

<div class="max-w-4xl mx-auto">
    <div class="bg-white/90 backdrop-blur-sm border border-emerald-100 rounded-2xl shadow-xl">
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf

            <div class="p-6 lg:p-8">
                {{-- Notifikasi error --}}
                @if ($errors->any())
                <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-red-700">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-circle-exclamation mt-0.5"></i>
                        <div>
                            <p class="font-semibold">Oops! Ada yang perlu dicek:</p>
                            <ul class="list-disc list-inside text-sm mt-1">
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Personal Information --}}
                <div class="mb-5">
                    <h3 class="text-lg font-semibold text-slate-800">Personal Information</h3>
                    <p class="text-sm text-slate-500">Lengkapi identitas pengguna.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    {{-- Name --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Full Name *</label>
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-emerald-600">
                                <i class="fas fa-user"></i>
                            </span>
                            <input
                                type="text" name="name" id="name" value="{{ old('name') }}" required
                                class="w-full rounded-xl border-2 border-slate-200 bg-white pl-10 pr-3 py-2.5
                         focus:border-emerald-500 focus:ring-4 focus:ring-emerald-200/60 transition"
                                placeholder="Nama lengkap">
                        </div>
                        @error('name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Email *</label>
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-emerald-600">
                                <i class="fas fa-envelope"></i>
                            </span>
                            <input
                                type="email" name="email" id="email" value="{{ old('email') }}" required
                                class="w-full rounded-xl border-2 border-slate-200 bg-white pl-10 pr-3 py-2.5
                         focus:border-emerald-500 focus:ring-4 focus:ring-emerald-200/60 transition"
                                placeholder="nama@email.com">
                        </div>
                        @error('email') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Phone --}}
                    <div>
                        <label for="phone_number" class="block text-sm font-medium text-slate-700 mb-1">Phone Number</label>
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-emerald-600">
                                <i class="fab fa-whatsapp"></i>
                            </span>
                            <input
                                type="tel" name="phone_number" id="phone_number" value="{{ old('phone_number') }}"
                                class="w-full rounded-xl border-2 border-slate-200 bg-white pl-10 pr-3 py-2.5
                         focus:border-emerald-500 focus:ring-4 focus:ring-emerald-200/60 transition"
                                placeholder="08xxxxxxxxxx">
                        </div>
                        @error('phone_number') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Role --}}
                    <div>
                        <label for="role" class="block text-sm font-medium text-slate-700 mb-1">Role *</label>
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-emerald-600">
                                <i class="fas fa-user-shield"></i>
                            </span>
                            <select
                                name="role" id="role" required
                                class="w-full appearance-none rounded-xl border-2 border-slate-200 bg-white pl-10 pr-10 py-2.5
                         focus:border-emerald-500 focus:ring-4 focus:ring-emerald-200/60 transition">
                                <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="technician" {{ old('role') == 'technician' ? 'selected' : '' }}>Technician</option>
                            </select>
                            <span class="pointer-events-none absolute right-3 inset-y-0 flex items-center text-slate-400">
                                <i class="fas fa-chevron-down"></i>
                            </span>
                        </div>
                        @error('role') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Address --}}
                    <div class="md:col-span-2">
                        <label for="address" class="block text-sm font-medium text-slate-700 mb-1">Address</label>
                        <div class="relative">
                            <span class="pointer-events-none absolute top-2.5 left-3 flex items-start text-emerald-600">
                                <i class="fas fa-map-marker-alt mt-0.5"></i>
                            </span>
                            <textarea
                                name="address" id="address" rows="3"
                                class="w-full rounded-xl border-2 border-slate-200 bg-white pl-10 pr-3 py-2.5
                         focus:border-emerald-500 focus:ring-4 focus:ring-emerald-200/60 transition"
                                placeholder="Alamat lengkap">{{ old('address') }}</textarea>
                        </div>
                        @error('address') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Account Security --}}
                <div class="mb-5">
                    <h3 class="text-lg font-semibold text-slate-800">Account Security</h3>
                    <p class="text-sm text-slate-500">Setel kata sandi awal untuk akun baru.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Password --}}
                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Password *</label>
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-emerald-600">
                                <i class="fas fa-key"></i>
                            </span>
                            <input
                                type="password" name="password" id="password" required
                                class="w-full rounded-xl border-2 border-slate-200 bg-white pl-10 pr-3 py-2.5
                         focus:border-emerald-500 focus:ring-4 focus:ring-emerald-200/60 transition"
                                placeholder="Minimal 8 karakter">
                        </div>
                        @error('password') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Confirm Password --}}
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1">Confirm Password *</label>
                        <div class="relative">
                            <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-emerald-600">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input
                                type="password" name="password_confirmation" id="password_confirmation" required
                                class="w-full rounded-xl border-2 border-slate-200 bg-white pl-10 pr-3 py-2.5
                         focus:border-emerald-500 focus:ring-4 focus:ring-emerald-200/60 transition"
                                placeholder="Ulangi password">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex flex-col sm:flex-row justify-end gap-3 border-t border-slate-100 bg-slate-50/60 px-6 lg:px-8 py-5 rounded-b-2xl">
                <a href="{{ route('admin.users.index') }}"
                    class="inline-flex items-center gap-2 rounded-xl border-2 border-slate-200 bg-white px-5 py-2.5
                    text-slate-700 hover:bg-slate-100 transition">
                    <i class="fas fa-chevron-left"></i> Cancel
                </a>
                <button type="submit"
                    class="inline-flex items-center gap-2 rounded-xl px-5 py-2.5 text-white
                         bg-gradient-to-r from-emerald-600 via-teal-600 to-sky-600
                         hover:from-emerald-700 hover:via-teal-700 hover:to-sky-700
                         shadow-lg shadow-emerald-600/20">
                    <i class="fas fa-user-plus"></i> Create User
                </button>
            </div>
        </form>
    </div>
</div>
@endsection