@extends('technician.layout')

@section('title','Manajemen COD')
@section('header','Manajemen COD')
@section('subheader','Setor dan lacak COD yang Anda kumpulkan')

@section('content')
@php
$cod_list = $cod_list ?? collect();
@endphp

<div class="card lift p-5 mb-6">
    <form action="{{ route('technician.cod.index') }}" method="GET" class="flex gap-2">
        <div class="relative flex-1">
            <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
            <input type="text" name="q" value="{{ request('q') }}"
                placeholder="Cari booking / nama pelanggan …"
                class="w-full pl-10 pr-3 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-sky-500">
        </div>
        <button class="px-4 py-2 rounded-xl bg-sky-600 text-white hover:bg-sky-700">Cari</button>
    </form>
</div>

<div class="card lift overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-slate-50/70">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Booking</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Pelanggan</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Jumlah</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($cod_list as $p)
                <tr class="hover:bg-slate-50/60">
                    <td class="px-6 py-4">#{{ $p->booking_id }}</td>
                    <td class="px-6 py-4">{{ $p->booking->user->name ?? 'Pelanggan' }}</td>
                    <td class="px-6 py-4 font-semibold text-sky-700">Rp {{ number_format((int)$p->amount,0,',','.') }}</td>
                    <td class="px-6 py-4">
                        <span class="chip
              @if($p->status=='pending') bg-amber-50 text-amber-700
              @elseif($p->status=='pending_verification') bg-indigo-50 text-indigo-700
              @elseif($p->status=='paid') bg-emerald-50 text-emerald-700
              @else bg-slate-50 text-slate-700 @endif">
                            {{ str_replace('_',' ',ucfirst($p->status)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        @if($p->status === 'pending')
                        <button class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-sky-600 text-white hover:bg-sky-700 text-sm"
                            onclick="openCodModal({{ $p->id }}, {{ (int)$p->amount }})">
                            <i class="fa-solid fa-hand-holding-dollar"></i> Setor
                        </button>
                        @else
                        <span class="text-slate-400 text-sm">-</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-10 text-center text-slate-500">Belum ada data COD.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(method_exists($cod_list,'hasPages') && $cod_list->hasPages())
    <div class="px-6 py-3 border-t border-slate-100 bg-slate-50/60">
        {{ $cod_list->links() }}
    </div>
    @endif
</div>

{{-- Modal reuse dari dashboard --}}
<div id="codModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/40" onclick="closeCodModal()"></div>
    <div class="relative z-10 max-w-md w-full mx-auto mt-24 card p-6">
        <h4 class="text-lg font-semibold mb-2">Setor COD</h4>
        <p class="text-sm text-slate-600 mb-4">Konfirmasi jumlah uang yang diterima dan unggah bukti (opsional).</p>

        <form id="codForm" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Jumlah Diterima</label>
                <input type="number" name="cod_received_amount" id="cod_received_amount"
                    class="w-full rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-sky-500"
                    min="0" required>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium mb-1">Bukti Pembayaran (opsional)</label>
                <input type="file" name="cod_proof" accept="image/*"
                    class="w-full rounded-xl border border-slate-200 px-3 py-2 focus:ring-2 focus:ring-sky-500">
            </div>

            <div class="flex items-center justify-end gap-2">
                <button type="button" class="px-4 py-2 rounded-xl border border-slate-200 hover:bg-slate-50"
                    onclick="closeCodModal()">Batal</button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-sky-600 text-white hover:bg-sky-700">
                    Setor Sekarang
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
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
@endpush
@endsection