@extends('admin.layout')

@section('title', 'Edit User - ServisAC')

{{-- Header atas (pakai header layout yang sudah ada) --}}
@section('header', 'Edit User')
@section('subheader', 'Perbarui informasi akun & kredensial pengguna.')

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
{{-- Banner tipis agar senada dengan tema login, tidak mengganggu konten --}}
<div class="mb-6 rounded-2xl bg-gradient-to-r from-emerald-600 via-teal-600 to-sky-600 px-6 py-4 text-white shadow-md">
    <div class="flex items-center gap-3">
        <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-white/15 ring-1 ring-white/20">
            <i class="fas fa-user-edit text-white text-lg"></i>
        </span>
        <div>
            <p class="text-sm/5 text-emerald-50">Form Pengguna</p>
            <h2 class="text-xl font-semibold">Perbarui Data User</h2>
        </div>
    </div>
</div>

{{-- Kartu form utama --}}
<div class="bg-white/90 backdrop-blur-sm border border-emerald-100 rounded-2xl shadow-xl">
    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

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

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Name --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Full Name *</label>
                    <div class="relative">
                        <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-emerald-600">
                            <i class="fas fa-user"></i>
                        </span>
                        <input
                            type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                            class="w-full rounded-xl border-2 border-slate-200 bg-white pl-10 pr-3 py-2.5
                       focus:border-emerald-500 focus:ring-4 focus:ring-emerald-200/60 transition"
                            placeholder="Nama lengkap">
                    </div>
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Email *</label>
                    <div class="relative">
                        <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-emerald-600">
                            <i class="fas fa-envelope"></i>
                        </span>
                        <input
                            type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                            class="w-full rounded-xl border-2 border-slate-200 bg-white pl-10 pr-3 py-2.5
                       focus:border-emerald-500 focus:ring-4 focus:ring-emerald-200/60 transition"
                            placeholder="nama@email.com">
                    </div>
                </div>

                {{-- Phone --}}
                <div>
                    <label for="phone_number" class="block text-sm font-medium text-slate-700 mb-1">Phone Number</label>
                    <div class="relative">
                        <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-emerald-600">
                            <i class="fab fa-whatsapp"></i>
                        </span>
                        <input
                            type="tel" id="phone_number" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}"
                            class="w-full rounded-xl border-2 border-slate-200 bg-white pl-10 pr-3 py-2.5
                       focus:border-emerald-500 focus:ring-4 focus:ring-emerald-200/60 transition"
                            placeholder="08xxxxxxxxxx">
                    </div>
                </div>

                {{-- Role --}}
                <div>
                    <label for="role" class="block text-sm font-medium text-slate-700 mb-1">Role *</label>
                    <div class="relative">
                        <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-emerald-600">
                            <i class="fas fa-user-shield"></i>
                        </span>
                        <select
                            id="role" name="role" required
                            class="w-full appearance-none rounded-xl border-2 border-slate-200 bg-white pl-10 pr-10 py-2.5
                       focus:border-emerald-500 focus:ring-4 focus:ring-emerald-200/60 transition">
                            <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User</option>
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="technician" {{ old('role', $user->role) == 'technician' ? 'selected' : '' }}>Technician</option>
                        </select>
                        <span class="pointer-events-none absolute right-3 inset-y-0 flex items-center text-slate-400">
                            <i class="fas fa-chevron-down"></i>
                        </span>
                    </div>
                </div>

                {{-- Address --}}
                <div class="md:col-span-2">
                    <label for="address" class="block text-sm font-medium text-slate-700 mb-1">Address</label>
                    <div class="relative">
                        <span class="pointer-events-none absolute top-2.5 left-3 flex items-start text-emerald-600">
                            <i class="fas fa-map-marker-alt mt-0.5"></i>
                        </span>
                        <textarea
                            id="address" name="address" rows="3"
                            class="w-full rounded-xl border-2 border-slate-200 bg-white pl-10 pr-3 py-2.5
                       focus:border-emerald-500 focus:ring-4 focus:ring-emerald-200/60 transition"
                            placeholder="Alamat lengkap">{{ old('address', $user->address) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Change Password --}}
            <div class="mt-10 mb-5">
                <h3 class="text-lg font-semibold text-slate-800">Change Password</h3>
                <p class="text-sm text-slate-500">Kosongkan jika tidak ingin mengubah kata sandi.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700 mb-1">New Password</label>
                    <div class="relative">
                        <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-emerald-600">
                            <i class="fas fa-key"></i>
                        </span>
                        <input
                            type="password" id="password" name="password" autocomplete="new-password"
                            class="w-full rounded-xl border-2 border-slate-200 bg-white pl-10 pr-3 py-2.5
                       focus:border-emerald-500 focus:ring-4 focus:ring-emerald-200/60 transition"
                            placeholder="Kata sandi baru">
                    </div>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1">Confirm New Password</label>
                    <div class="relative">
                        <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-emerald-600">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input
                            type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password"
                            class="w-full rounded-xl border-2 border-slate-200 bg-white pl-10 pr-3 py-2.5
                       focus:border-emerald-500 focus:ring-4 focus:ring-emerald-200/60 transition"
                            placeholder="Ulangi kata sandi">
                    </div>
                </div>
            </div>

            {{-- Account Info (read-only) --}}
            <div class="mt-10 mb-5">
                <h3 class="text-lg font-semibold text-slate-800">Account Information</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-slate-500 mb-1">Email Status</p>
                    <span class="inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1.5 text-emerald-700 text-sm font-semibold">
                        <i class="fas fa-badge-check"></i>
                        @if($user->email_verified_at) Verified @else Not Verified @endif
                    </span>
                </div>
                <div>
                    <p class="text-sm text-slate-500 mb-1">Member Since</p>
                    <span class="inline-flex items-center gap-2 rounded-full border border-sky-200 bg-sky-50 px-3 py-1.5 text-sky-700 text-sm font-semibold">
                        <i class="fas fa-calendar-alt"></i>
                        {{ $user->created_at->format('M d, Y') }}
                    </span>
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
                <i class="fas fa-save"></i> Save Changes
            </button>
        </div>
    </form>
</div>
@endsection