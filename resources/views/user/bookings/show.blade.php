@extends('layouts.user')

@section('title', 'Detail Booking - ServisAC')
@section('header-title', 'Detail Booking')

@section('content')
<div class="max-w-4xl mx-auto">

    {{-- Kembali --}}
    <div class="mb-4">
        <a href="{{ route('user.bookings') }}"
            class="inline-flex items-center gap-2 text-slate-700 hover:text-slate-900">
            <span class="h-8 w-8 grid place-items-center rounded-lg ring-1 ring-sky-200 bg-white">
                <i class="fas fa-arrow-left"></i>
            </span>
            <span class="font-medium">Kembali ke Daftar Booking</span>
        </a>
    </div>

    {{-- Kartu Utama --}}
    <div class="relative rounded-2xl bg-white/85 backdrop-blur border border-sky-100 shadow-sm overflow-hidden">
        {{-- Aksen garis atas --}}
        <div class="absolute inset-x-0 top-0 h-1" style="background:linear-gradient(90deg,#22d3ee,#2563eb)"></div>

        {{-- Header --}}
        <div class="p-6 border-b border-sky-100">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                <div>
                    <h2 class="text-2xl font-extrabold text-slate-900">
                        Booking #{{ $booking->id }}
                    </h2>
                    <p class="text-slate-600">
                        Tanggal:
                        <span class="font-semibold text-slate-800">
                            {{ $booking->booking_date->format('d F Y \p\u\k\u\l H:i') }}
                        </span>
                    </p>
                </div>

                {{-- Status chip --}}
                <div>
                    <span class="px-4 py-2 rounded-full text-sm font-bold ring-1 whitespace-nowrap
            @if($booking->status=='pending' || $booking->status=='pending_verification') bg-amber-50 text-amber-700 ring-amber-200
            @elseif($booking->status=='confirmed') bg-sky-50 text-sky-700 ring-sky-200
            @elseif($booking->status=='assigned') bg-indigo-50 text-indigo-700 ring-indigo-200
            @elseif($booking->status=='in_progress') bg-violet-50 text-violet-700 ring-violet-200
            @elseif($booking->status=='completed') bg-emerald-50 text-emerald-700 ring-emerald-200
            @elseif($booking->status=='cancelled') bg-rose-50 text-rose-700 ring-rose-200
            @elseif($booking->status=='pending_refund') bg-orange-50 text-orange-700 ring-orange-200
            @elseif($booking->status=='refunded') bg-slate-50 text-slate-700 ring-slate-200
            @else bg-slate-50 text-slate-700 ring-slate-200 @endif">
                        {{ str_replace('_', ' ', ucfirst($booking->status)) }}
                    </span>
                </div>
            </div>
        </div>

        <div class="p-6 space-y-8">

            {{-- Detail Layanan --}}
            <div>
                <h3 class="text-xl font-extrabold text-slate-900 mb-4 flex items-center gap-3">
                    <span class="h-9 w-9 rounded-lg grid place-items-center text-sky-700"
                        style="background:linear-gradient(135deg,#dff4ff,#bde7ff);box-shadow:inset 0 0 0 2px #fff;">
                        <i class="fas fa-tools"></i>
                    </span>
                    Detail Layanan
                </h3>

                <div class="rounded-xl border border-sky-100 overflow-hidden bg-white/70">
                    <table class="min-w-full">
                        <thead class="bg-sky-50/60">
                            <tr class="text-slate-600 text-xs uppercase tracking-wide">
                                <th class="px-4 py-3 text-left font-bold">Layanan</th>
                                <th class="px-4 py-3 text-center font-bold">Jumlah</th>
                                <th class="px-4 py-3 text-right font-bold">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-sky-100">
                            @foreach($booking->services as $service)
                            <tr class="text-sm">
                                <td class="px-4 py-3 font-medium text-slate-900">{{ $service->name }}</td>
                                <td class="px-4 py-3 text-center text-slate-900">{{ $service->pivot->quantity }}</td>
                                <td class="px-4 py-3 text-right font-semibold text-slate-900">
                                    Rp {{ number_format($service->pivot->price * $service->pivot->quantity, 0, ',', '.') }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-sky-50/60">
                            <tr>
                                <td colspan="2" class="px-4 py-3 text-right text-base font-semibold text-slate-900">Total</td>
                                <td class="px-4 py-3 text-right text-xl font-extrabold bg-clip-text text-transparent"
                                    style="background-image:linear-gradient(90deg,#2563eb,#06b6d4);">
                                    Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            {{-- Teknisi & Alamat --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Teknisi --}}
                <div>
                    <h3 class="text-xl font-extrabold text-slate-900 mb-4 flex items-center gap-3">
                        <span class="h-9 w-9 rounded-lg grid place-items-center text-indigo-700"
                            style="background:linear-gradient(135deg,#e2e7ff,#ccd6ff);box-shadow:inset 0 0 0 2px #fff;">
                            <i class="fas fa-user-cog"></i>
                        </span>
                        Teknisi
                    </h3>

                    <div class="rounded-xl border border-sky-100 bg-white/70 p-4">
                        @if($booking->technician)
                        <div class="flex items-center gap-3">
                            <div class="h-12 w-12 rounded-full grid place-items-center text-indigo-600"
                                style="background:linear-gradient(135deg,#eef1ff,#e0e7ff);">
                                <i class="fas fa-user text-lg"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-slate-900">{{ $booking->technician->name }}</p>
                                <p class="text-sm text-slate-600">Akan segera menghubungi Anda</p>
                            </div>
                        </div>
                        @else
                        <div class="flex items-center gap-3">
                            <div class="h-12 w-12 rounded-full grid place-items-center text-slate-500 bg-slate-100">
                                <i class="fas fa-user-clock"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-slate-800">Belum Ditugaskan</p>
                                <p class="text-sm text-slate-500">Menunggu konfirmasi admin</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Alamat --}}
                <div>
                    <h3 class="text-xl font-extrabold text-slate-900 mb-4 flex items-center gap-3">
                        <span class="h-9 w-9 rounded-lg grid place-items-center text-rose-700"
                            style="background:linear-gradient(135deg,#ffeef2,#ffdfe6);box-shadow:inset 0 0 0 2px #fff;">
                            <i class="fas fa-map-marker-alt"></i>
                        </span>
                        Alamat Pengerjaan
                    </h3>

                    <div class="rounded-xl border border-sky-100 bg-white/70 p-4">
                        <p class="text-slate-800 font-medium leading-relaxed">{{ $booking->address }}</p>
                    </div>
                </div>
            </div>

            {{-- Catatan --}}
            @if($booking->notes)
            <div>
                <h3 class="text-xl font-extrabold text-slate-900 mb-4 flex items-center gap-3">
                    <span class="h-9 w-9 rounded-lg grid place-items-center text-amber-700"
                        style="background:linear-gradient(135deg,#fff6e6,#ffedd1);box-shadow:inset 0 0 0 2px #fff;">
                        <i class="fas fa-sticky-note"></i>
                    </span>
                    Catatan dari Anda
                </h3>
                <div class="rounded-xl border border-sky-100 bg-white/70 p-4">
                    <p class="text-slate-700 whitespace-pre-wrap italic leading-relaxed">"{{ $booking->notes }}"</p>
                </div>
            </div>
            @endif

            {{-- Pembayaran --}}
            @if($booking->payment)
            <div>
                <h3 class="text-xl font-extrabold text-slate-900 mb-4 flex items-center gap-3">
                    <span class="h-9 w-9 rounded-lg grid place-items-center text-emerald-700"
                        style="background:linear-gradient(135deg,#e9fbf3,#d3f7e6);box-shadow:inset 0 0 0 2px #fff;">
                        <i class="fas fa-credit-card"></i>
                    </span>
                    Detail Pembayaran
                </h3>

                <div class="rounded-xl border border-sky-100 bg-white/70 divide-y divide-sky-100">
                    <div class="p-4 flex justify-between items-center">
                        <span class="text-slate-600">Tipe Pembayaran</span>
                        <span class="font-semibold text-slate-900">
                            {{ ucfirst(str_replace('_', ' ', $booking->payment->payment_type)) }}
                        </span>
                    </div>

                    @if(!empty($booking->payment->transfer_method))
                    <div class="p-4 flex justify-between items-center">
                        <span class="text-slate-600">Metode Transfer</span>
                        <span class="font-semibold text-slate-900 flex items-center gap-2">
                            @if($booking->payment->transfer_method === 'dana')
                            <i class="fa-solid fa-mobile-screen-button text-cyan-600"></i> DANA
                            @elseif($booking->payment->transfer_method === 'bca')
                            <i class="fa-solid fa-building-columns text-indigo-600"></i> BCA
                            @else
                            {{ strtoupper($booking->payment->transfer_method) }}
                            @endif
                        </span>
                    </div>
                    @endif

                    <div class="p-4 flex justify-between items-center">
                        <span class="text-slate-600">Status Pembayaran</span>
                        <span class="font-semibold text-slate-900">{{ ucfirst(str_replace('_', ' ', $booking->payment->status)) }}</span>
                    </div>

                    <div class="p-4 flex justify-between items-center">
                        <span class="text-slate-600">Verifikasi Admin</span>
                        <span class="font-semibold text-slate-900">{{ ucfirst($booking->payment->verification_status) }}</span>
                    </div>

                    @if($booking->payment->payment_proof)
                    <div class="p-4">
                        <a href="{{ Storage::url($booking->payment->payment_proof) }}" target="_blank"
                            class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl font-semibold text-sky-700 ring-1 ring-sky-200 bg-white hover:bg-sky-50 transition">
                            <i class="fas fa-eye"></i>
                            Lihat Bukti Pembayaran
                        </a>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            {{-- Rating (jika selesai) --}}
            @if($booking->status == 'completed')
            <div class="border-t border-sky-100 pt-6" id="rating-form">
                <h3 class="text-xl font-extrabold text-slate-900 mb-4">Beri Ulasan Layanan Ini</h3>

                @if($booking->rating)
                <div>
                    <p class="text-slate-700 mb-2 font-medium">Ulasan Anda:</p>
                    <div class="flex text-yellow-400 text-xl">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <=$booking->rating->rating)
                            <i class="fas fa-star"></i>
                            @else
                            <i class="far fa-star"></i>
                            @endif
                            @endfor
                    </div>
                    @if($booking->rating->review)
                    <p class="text-slate-600 italic mt-3 p-4 rounded-xl border border-sky-100 bg-white/70">"{{ $booking->rating->review }}"</p>
                    @endif
                </div>
                @else
                <form action="{{ route('user.bookings.rate', $booking->id) }}" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <label class="text-slate-700 font-medium mb-2 block">Rating Anda (Bintang):</label>
                        <select name="rating"
                            class="w-full md:w-1/2 rounded-xl border border-sky-100 bg-white focus:ring-4 focus:ring-sky-200/60 focus:border-sky-300">
                            <option value="5">⭐⭐⭐⭐⭐ (Luar Biasa)</option>
                            <option value="4">⭐⭐⭐⭐ (Baik)</option>
                            <option value="3" selected>⭐⭐⭐ (Cukup)</option>
                            <option value="2">⭐⭐ (Kurang)</option>
                            <option value="1">⭐ (Buruk)</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-slate-700 font-medium mb-2 block">Ulasan Anda (Opsional):</label>
                        <textarea name="review" rows="4"
                            class="w-full rounded-xl border border-sky-100 bg-white focus:ring-4 focus:ring-sky-200/60 focus:border-sky-300"
                            placeholder="Bagikan pengalaman Anda..."></textarea>
                    </div>
                    <button type="submit"
                        class="mt-2 px-6 py-2.5 rounded-xl font-semibold text-white shadow-md hover:shadow-lg transition"
                        style="background:linear-gradient(90deg,#3b82f6,#06b6d4);">
                        Kirim Ulasan
                    </button>
                </form>
                @endif
            </div>
            @endif

            {{-- Status Refund --}}
            @if($booking->payment && $booking->payment->refund_status)
            <div class="border-t border-sky-100 pt-6">
                <h3 class="text-xl font-extrabold text-slate-900 mb-4">Informasi Refund</h3>

                @if($booking->payment->refund_status == 'pending')
                <div class="p-4 rounded-xl bg-amber-50 ring-1 ring-amber-200">
                    <p class="font-semibold text-amber-800">Permintaan refund Anda sedang ditinjau oleh admin.</p>
                    <p class="text-sm text-amber-700 mt-1">Alasan Anda: "{{ $booking->payment->refund_reason }}"</p>
                </div>
                @elseif($booking->payment->refund_status == 'approved')
                <div class="p-4 rounded-xl bg-emerald-50 ring-1 ring-emerald-200">
                    <p class="font-semibold text-emerald-800">Permintaan refund Anda telah disetujui.</p>
                    <p class="text-sm text-emerald-700 mt-1">Catatan Admin: "{{ $booking->payment->refund_admin_notes ?? 'Disetujui.' }}"</p>
                </div>
                @elseif($booking->payment->refund_status == 'rejected')
                <div class="p-4 rounded-xl bg-rose-50 ring-1 ring-rose-200">
                    <p class="font-semibold text-rose-800">Permintaan refund Anda ditolak.</p>
                    <p class="text-sm text-rose-700 mt-1">Alasan Admin: "{{ $booking->payment->refund_admin_notes ?? 'Tidak memenuhi syarat.' }}"</p>
                </div>
                @endif
            </div>
            @endif
        </div>

        {{-- Footer Aksi --}}
        <div class="border-t border-sky-100 p-6 bg-white/70 flex flex-col sm:flex-row justify-between items-center gap-3">
            <a href="{{ route('user.bookings') }}"
                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl font-semibold
                ring-1 ring-sky-200 text-slate-700 bg-white hover:bg-sky-50 transition">
                Kembali ke Daftar
            </a>

            <div class="flex w-full sm:w-auto gap-3">
                {{-- Batalkan --}}
                @if(in_array($booking->status, ['pending', 'confirmed']))
                <form action="{{ route('user.bookings.cancel', $booking->id) }}" method="POST" class="w-full sm:w-auto"
                    onsubmit="return confirm('Apakah Anda yakin ingin membatalkan booking ini?')">
                    @csrf
                    <button type="submit"
                        class="w-full sm:w-auto inline-flex justify-center px-5 py-2.5 rounded-xl font-semibold text-white shadow-md hover:shadow-lg transition"
                        style="background:linear-gradient(90deg,#ef4444,#dc2626);">
                        Batalkan Booking
                    </button>
                </form>
                @endif

                {{-- Minta Refund --}}
                @if($booking->payment && $booking->payment->status == 'paid' && $booking->payment->refund_status == null)
                <button type="button"
                    onclick="document.getElementById('requestRefundModal').classList.remove('hidden')"
                    class="w-full sm:w-auto inline-flex justify-center px-5 py-2.5 rounded-xl font-semibold text-white shadow-md hover:shadow-lg transition"
                    style="background:linear-gradient(90deg,#f59e0b,#f97316);">
                    Minta Refund
                </button>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Modal Refund --}}
@if($booking->payment)
@php $payment_id_for_refund = $booking->payment->id; @endphp

<div id="requestRefundModal" class="fixed z-50 inset-0 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true"
            onclick="document.getElementById('requestRefundModal').classList.add('hidden')">
            <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-sky-100">
            <div class="px-6 py-5 border-b border-sky-100"
                style="background:linear-gradient(180deg,#ffffff,#fbfdff);">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-xl grid place-items-center text-orange-700"
                        style="background:linear-gradient(135deg,#fff3e6,#ffe3c7);box-shadow:inset 0 0 0 2px #fff;">
                        <i class="fas fa-undo"></i>
                    </div>
                    <h3 class="text-lg font-extrabold text-slate-900">Permintaan Refund</h3>
                </div>
            </div>

            <form action="{{ route('user.payments.requestRefund', $payment_id_for_refund) }}" method="POST">
                @csrf
                <div class="px-6 py-5">
                    <label for="refund_reason" class="block text-sm font-medium text-slate-700 mb-2">Alasan Refund *</label>
                    <textarea name="refund_reason" id="refund_reason" rows="4"
                        class="w-full rounded-xl border border-sky-100 bg-white focus:ring-4 focus:ring-sky-200/60 focus:border-sky-300"
                        placeholder="Jelaskan alasan Anda meminta refund..." required minlength="10"></textarea>
                    <p class="text-xs text-slate-500 mt-1">Admin akan meninjau permintaan Anda. Keputusan refund bersifat final.</p>
                </div>

                <div class="px-6 py-4 bg-sky-50/60 border-t border-sky-100 sm:flex sm:flex-row-reverse gap-2">
                    <button type="submit"
                        class="w-full sm:w-auto inline-flex justify-center rounded-xl px-5 py-2.5 font-semibold text-white shadow-md hover:shadow-lg transition"
                        style="background:linear-gradient(90deg,#f59e0b,#f97316);">
                        Kirim Permintaan
                    </button>
                    <button type="button"
                        onclick="document.getElementById('requestRefundModal').classList.add('hidden')"
                        class="mt-3 sm:mt-0 w-full sm:w-auto inline-flex justify-center rounded-xl border border-sky-200 px-5 py-2.5 bg-white text-slate-700 font-semibold hover:bg-sky-50 transition">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Jika ada error validasi di form refund, modalnya jangan ditutup
        <?php if ($errors->has('refund_reason')) { ?>
            document.getElementById('requestRefundModal').classList.remove('hidden');
        <?php } ?>
    });
</script>
@endpush