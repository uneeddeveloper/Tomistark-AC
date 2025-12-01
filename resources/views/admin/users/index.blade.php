@extends('admin.layout')

@section('title', 'Users Management - ServisAC')

{{-- Header di bagian atas layout --}}
@section('header', 'Users Management')
@section('subheader', 'Kelola akun pengguna, peran, dan kontak.')
@section('header-actions')
<a href="{{ route('admin.users.create') }}"
    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-sky-600 text-white shadow-sm hover:bg-sky-700">
    <i class="fas fa-user-plus"></i> Add User
</a>
@endsection

@push('styles')
<style>
    .card {
        background: #fff;
        border: 1px solid rgba(14, 165, 233, .15);
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

    /* Pastikan konten sel membungkus dengan baik (termasuk email panjang) */
    .wrap {
        white-space: normal;
        word-break: break-word;
        overflow-wrap: anywhere;
    }

    .breakall {
        word-break: break-all;
    }
</style>
@endpush

@section('content')
<div class="-mx-6 -mt-6 p-6 lg:-mx-10 lg:-mt-10 lg:p-10 rounded-3xl"
    style="background:linear-gradient(180deg,#f2f9ff 0%,#f6fbff 60%,#f8fcff 100%);">

    {{-- Search --}}
    <div class="card lift p-4 mb-6">
        <form action="{{ route('admin.users.index') }}" method="GET" class="w-full">
            <div class="flex">
                <div class="relative flex-1">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" name="search"
                        value="{{ request('search') }}"
                        placeholder="Search by name, email, or phone..."
                        class="w-full pl-10 pr-3 py-2 rounded-l-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent">
                </div>
                <button type="submit"
                    class="px-4 py-2 bg-sky-600 text-white rounded-r-xl hover:bg-sky-700">
                    Search
                </button>
            </div>
        </form>
    </div>

    {{-- Table View (desktop & tablet) — TANPA SCROLL SAMPING --}}
    <div class="card lift overflow-hidden hidden md:block">
        {{-- Hapus overflow-x-auto agar tidak memicu scroll samping --}}
        <div>
            <table class="w-full table-fixed text-[15px]">
                {{-- Atur porsi lebar kolom agar muat di layar sedang --}}
                <colgroup>
                    <col style="width: 34%;">
                    <col style="width: 26%;">
                    <col style="width: 14%;">
                    <col style="width: 14%;">
                    <col style="width: 12%;"> {{-- actions --}}
                </colgroup>

                <thead class="bg-slate-50/70">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Name</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Contact</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Role</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Joined</th>
                        <th class="px-5 py-3 text-right text-xs font-semibold text-slate-500 uppercase">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $user)
                    <tr class="hover:bg-slate-50/60 align-top">
                        {{-- Name --}}
                        <td class="px-5 py-4">
                            <div class="flex items-start gap-3">
                                <div class="min-w-10 w-10 h-10 rounded-full bg-gradient-to-r from-sky-500 to-indigo-500 text-white grid place-items-center font-bold shrink-0">
                                    {{ strtoupper(substr($user->name,0,1)) }}
                                </div>
                                <div class="min-w-0">
                                    <div class="text-sm font-semibold text-slate-900 wrap">{{ $user->name }}</div>
                                    <div class="text-xs text-slate-500 breakall">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>

                        {{-- Contact --}}
                        <td class="px-5 py-4">
                            <div class="text-sm text-slate-800 wrap">{{ $user->phone_number ?? 'N/A' }}</div>
                        </td>

                        {{-- Role --}}
                        <td class="px-5 py-4">
                            <span class="chip wrap
                  @if($user->role=='admin') bg-purple-50 text-purple-700
                  @elseif($user->role=='technician') bg-indigo-50 text-indigo-700
                  @else bg-sky-50 text-sky-700 @endif">
                                <i class="fas fa-user-shield mr-1 text-[11px]"></i>{{ ucfirst($user->role) }}
                            </span>
                        </td>

                        {{-- Joined --}}
                        <td class="px-5 py-4 text-sm text-slate-600">
                            {{ $user->created_at->format('M d, Y') }}
                        </td>

                        {{-- Actions (kompak & tidak meluber) --}}
                        <td class="px-5 py-4">
                            <div class="flex flex-wrap items-center justify-end gap-2">
                                <a href="{{ route('admin.users.show',$user->id) }}"
                                    class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-white border border-slate-200 text-sky-700 hover:bg-sky-50">
                                    <i class="fas fa-eye"></i>
                                    <span class="hidden xl:inline">View</span>
                                </a>
                                <a href="{{ route('admin.users.edit',$user->id) }}"
                                    class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-white border border-slate-200 text-emerald-700 hover:bg-emerald-50">
                                    <i class="fas fa-edit"></i>
                                    <span class="hidden xl:inline">Edit</span>
                                </a>
                                <form action="{{ route('admin.users.delete',$user->id) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        onclick="return confirm('Are you sure you want to delete this user?')"
                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-white border border-slate-200 text-rose-700 hover:bg-rose-50">
                                        <i class="fas fa-trash-alt"></i>
                                        <span class="hidden xl:inline">Delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-slate-500">No users found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
        <div class="px-6 py-3 border-t border-slate-100 bg-slate-50/60">
            {{ $users->links() }}
        </div>
        @endif
    </div>

    {{-- Card View (mobile) --}}
    <div class="grid md:hidden gap-4">
        @forelse($users as $user)
        <div class="card lift p-4">
            <div class="flex items-start justify-between">
                <div class="flex items-center">
                    <div class="w-11 h-11 rounded-full bg-gradient-to-r from-sky-500 to-indigo-500 text-white grid place-items-center font-bold mr-3">
                        {{ strtoupper(substr($user->name,0,1)) }}
                    </div>
                    <div>
                        <div class="font-semibold text-slate-900 wrap">{{ $user->name }}</div>
                        <div class="text-xs text-slate-500 breakall">{{ $user->email }}</div>
                    </div>
                </div>
                <span class="chip
            @if($user->role=='admin') bg-purple-50 text-purple-700
            @elseif($user->role=='technician') bg-indigo-50 text-indigo-700
            @else bg-sky-50 text-sky-700 @endif">
                    {{ ucfirst($user->role) }}
                </span>
            </div>

            <div class="mt-3 grid grid-cols-2 gap-3 text-sm">
                <div>
                    <p class="text-slate-500">Phone</p>
                    <p class="font-medium text-slate-800 wrap">{{ $user->phone_number ?? 'N/A' }}</p>
                </div>
                <div class="text-right">
                    <p class="text-slate-500">Joined</p>
                    <p class="font-medium text-slate-800">{{ $user->created_at->format('M d, Y') }}</p>
                </div>
            </div>

            <div class="mt-4 flex items-center justify-end gap-2">
                <a href="{{ route('admin.users.show',$user->id) }}"
                    class="px-3 py-1.5 rounded-lg bg-white border border-slate-200 text-sky-700 hover:bg-sky-50">View</a>
                <a href="{{ route('admin.users.edit',$user->id) }}"
                    class="px-3 py-1.5 rounded-lg bg-white border border-slate-200 text-emerald-700 hover:bg-emerald-50">Edit</a>
                <form action="{{ route('admin.users.delete',$user->id) }}" method="POST" class="inline">
                    @csrf @method('DELETE')
                    <button type="submit"
                        onclick="return confirm('Are you sure you want to delete this user?')"
                        class="px-3 py-1.5 rounded-lg bg-white border border-slate-200 text-rose-700 hover:bg-rose-50">Delete</button>
                </form>
            </div>
        </div>
        @empty
        <div class="card lift p-6 text-center text-slate-500">No users found.</div>
        @endforelse

        @if($users->hasPages())
        <div class="px-1">
            {{ $users->links() }}
        </div>
        @endif
    </div>

</div>
@endsection