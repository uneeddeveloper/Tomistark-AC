@extends('layouts.user')

@section('title', 'Buat Booking Baru - ServisAC')
@section('header-title', 'Buat Booking Baru')

@section('content')
@php
// Ubah sesuai data bisnis Anda
$DANA_NUMBER = '081254692025';
$DANA_NAME = 'RommiStark';
$BCA_NUMBER = '7925546062 ';
$BCA_NAME = 'RommiStarkAC';
@endphp

<div class="max-w-4xl mx-auto">
    {{-- Form tetap multipart untuk upload bukti --}}
    <form action="{{ route('user.bookings.store') }}" method="POST" id="bookingForm" enctype="multipart/form-data">
        @csrf

        {{-- ===== Langkah 1: Pilih Layanan ===== --}}
        <div class="rounded-2xl bg-white/85 backdrop-blur border border-sky-100 shadow-sm p-6 lg:p-8 mb-6">
            <div class="flex items-center gap-3 mb-2">
                <div class="h-10 w-10 rounded-xl grid place-items-center text-sky-700"
                    style="background:linear-gradient(135deg,#dff4ff,#bde7ff); box-shadow:inset 0 0 0 2px #fff;">
                    <i class="fas fa-list-check"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-extrabold text-slate-900">Langkah 1: Pilih Layanan</h2>
                    <p class="text-slate-600">Pilih satu atau lebih layanan yang Anda butuhkan.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="services-container">
                @forelse($services as $service)
                {{-- Kartu Layanan --}}
                <div
                    class="_card group cursor-pointer rounded-2xl p-4 transition-all duration-200 ease-in-out border border-sky-100 bg-white hover:shadow-md hover:bg-sky-50/40"
                    class_card="service-card"
                    data-card="service">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-start">
                            <input type="checkbox" name="services[]" value="{{ $service->id }}"
                                id="service-{{ $service->id }}"
                                class="service-checkbox mt-1 h-5 w-5 text-sky-600 focus:ring-sky-500 border-sky-200 rounded"
                                data-price="{{ $service->price }}">
                            <div class="ml-3">
                                <label for="service-{{ $service->id }}" class="text-base font-semibold text-slate-900 cursor-pointer">
                                    {{ $service->name }}
                                </label>
                                <p class="text-sm text-slate-500 mt-1">{{ $service->description }}</p>
                                <p class="text-base font-extrabold bg-clip-text text-transparent mt-2"
                                    style="background-image:linear-gradient(90deg,#2563eb,#06b6d4);">
                                    Rp {{ number_format($service->price, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>

                        <div class="mt-1" class_quantity_wrapper>
                            <label for="quantity-{{ $service->id }}" class="text-[11px] font-bold tracking-wide text-slate-500 uppercase">Jumlah</label>
                            <input type="number" name="quantity[{{ $service->id }}]" id="quantity-{{ $service->id }}" value="1" min="1"
                                class="quantity-input w-20 px-2 py-1 rounded-lg border border-sky-200 text-sm text-center focus:outline-none focus:ring-2 focus:ring-sky-200 focus:border-sky-300"
                                data-service="{{ $service->id }}" disabled>
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-slate-500 md:col-span-2 text-center">Belum ada layanan yang tersedia saat ini.</p>
                @endforelse
            </div>

            @error('services')
            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>

        {{-- ===== Langkah 2: Detail Booking ===== --}}
        <div class="rounded-2xl bg-white/85 backdrop-blur border border-sky-100 shadow-sm p-6 lg:p-8 mb-6">
            <div class="flex items-center gap-3 mb-2">
                <div class="h-10 w-10 rounded-xl grid place-items-center text-indigo-700"
                    style="background:linear-gradient(135deg,#e2e7ff,#ccd6ff); box-shadow:inset 0 0 0 2px #fff;">
                    <i class="fas fa-calendar-days"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-extrabold text-slate-900">Langkah 2: Detail Booking</h2>
                    <p class="text-slate-600">Tentukan kapan dan di mana kami harus datang.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label for="booking_date" class="block text-xs font-bold tracking-wide text-slate-700 mb-2 uppercase">
                        Tanggal & Waktu Booking
                    </label>
                    <input type="datetime-local" name="booking_date" id="booking_date"
                        class="w-full px-4 py-2.5 rounded-xl border border-sky-100 bg-white text-slate-900
                   focus:outline-none focus:ring-4 focus:ring-sky-200/60 focus:border-sky-300 transition"
                        value="{{ old('booking_date') }}">
                    @error('booking_date') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="address" class="block text-xs font-bold tracking-wide text-slate-700 mb-2 uppercase">Alamat Lengkap</label>
                    <textarea name="address" id="address" rows="3"
                        class="w-full px-4 py-2.5 rounded-xl border border-sky-100 bg-white text-slate-900
                   focus:outline-none focus:ring-4 focus:ring-sky-200/60 focus:border-sky-300 transition"
                        placeholder="Masukkan alamat lengkap Anda...">{{ old('address', Auth::user()->address) }}</textarea>
                    @error('address') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="notes" class="block text-xs font-bold tracking-wide text-slate-700 mb-2 uppercase">Catatan (Opsional)</label>
                    <textarea name="notes" id="notes" rows="3"
                        class="w-full px-4 py-2.5 rounded-xl border border-sky-100 bg-white text-slate-900
                   focus:outline-none focus:ring-4 focus:ring-sky-200/60 focus:border-sky-300 transition"
                        placeholder="cth: AC tidak dingin, unit berisik, butuh 2 teknisi...">{{ old('notes') }}</textarea>
                    @error('notes') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Ringkasan cepat + CTA --}}
        <div class="mt-6 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
            <div class="flex-1 rounded-2xl bg-white/85 backdrop-blur border border-sky-100 shadow-sm px-4 py-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="h-9 w-9 rounded-xl grid place-items-center text-cyan-700"
                            style="background:linear-gradient(135deg,#dff7ff,#bfe8ff); box-shadow:inset 0 0 0 2px #fff;">
                            <i class="fas fa-receipt"></i>
                        </div>
                        <div>
                            <p class="text-[11px] font-bold tracking-wide text-slate-500 uppercase">Total Sementara</p>
                            <p id="pageTotalPrice" class="text-lg font-extrabold text-slate-900">Rp 0</p>
                        </div>
                    </div>
                    <span id="pageSelectedCount" class="px-3 py-1 rounded-full text-xs font-bold bg-sky-50 text-sky-700 ring-1 ring-sky-200">
                        0 layanan dipilih
                    </span>
                </div>
            </div>

            <button type="button" id="openBookingModalBtn"
                class="px-6 py-3 rounded-2xl font-semibold text-white shadow-md hover:shadow-lg transition
               focus:outline-none focus:ring-4 focus:ring-sky-200/70"
                style="background:linear-gradient(90deg,#3b82f6,#06b6d4);">
                Lanjutkan ke Pembayaran
            </button>
        </div>

        {{-- ===== Modal Konfirmasi ===== --}}
        <div id="bookingModal" class="fixed z-50 inset-0 overflow-y-auto hidden">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">

                {{-- Overlay --}}
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm"></div>
                </div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                {{-- Card Modal --}}
                <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-sky-100">
                    <div class="px-6 py-5 border-b border-sky-100"
                        style="background:linear-gradient(180deg,#ffffff,#fbfdff);">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-xl grid place-items-center text-sky-700"
                                style="background:linear-gradient(135deg,#dff4ff,#bde7ff); box-shadow:inset 0 0 0 2px #fff;">
                                <i class="fas fa-file-invoice-dollar"></i>
                            </div>
                            <h3 class="text-xl font-extrabold text-slate-900">Konfirmasi Pesanan Anda</h3>
                        </div>
                    </div>

                    <div class="px-6 py-5 space-y-5">
                        {{-- Total Tagihan --}}
                        <div class="rounded-xl bg-sky-50/60 border border-sky-100 p-4">
                            <span class="text-sm font-semibold text-slate-700">Total Tagihan:</span>
                            <span id="modalTotalPrice" class="text-2xl font-extrabold text-slate-900 block mt-1">Rp 0</span>
                        </div>

                        {{-- Tipe Pembayaran --}}
                        <div>
                            <label for="modalPaymentType" class="block text-xs font-bold tracking-wide text-slate-700 mb-2 uppercase">
                                Tipe Pembayaran *
                            </label>
                            <select name="payment_type" id="modalPaymentType"
                                class="w-full px-3 py-2.5 rounded-xl border border-sky-100 bg-white text-slate-900
                       focus:outline-none focus:ring-4 focus:ring-sky-200/60 focus:border-sky-300 transition">
                                <option value="full">Bayar Transfer</option>
                                <option value="cod">Bayar di Tempat (COD)</option>
                            </select>
                        </div>

                        {{-- Metode Transfer (muncul jika full/down_payment) --}}
                        <div id="transferMethodSection" class="hidden">
                            <label class="block text-xs font-bold tracking-wide text-slate-700 mb-2 uppercase">
                                Metode Transfer *
                            </label>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                {{-- DANA --}}
                                <label for="transfer-dana" class="cursor-pointer">
                                    <input type="radio" id="transfer-dana" name="transfer_method" value="dana" class="peer sr-only">
                                    <div class="rounded-xl ring-1 ring-sky-200 bg-white p-4 h-full
                              peer-checked:ring-2 peer-checked:ring-sky-400 peer-checked:bg-sky-50 transition">
                                        <div class="flex items-center gap-2 mb-2">
                                            <div class="h-8 w-8 rounded-lg grid place-items-center text-cyan-700"
                                                style="background:linear-gradient(135deg,#dff7ff,#bfe8ff); box-shadow:inset 0 0 0 2px #fff;">
                                                <i class="fa-solid fa-mobile-screen-button"></i>
                                            </div>
                                            <span class="font-semibold text-slate-900">DANA</span>
                                        </div>
                                        <p class="text-sm text-slate-600 leading-5">
                                            No. DANA: <strong class="text-slate-900" id="danaNumber">{{ $DANA_NUMBER }}</strong><br>
                                            a.n. {{ $DANA_NAME }}
                                        </p>
                                        <button type="button" class="mt-2 text-xs font-semibold px-3 py-1.5 rounded-lg bg-sky-50 text-sky-700 ring-1 ring-sky-200 hover:bg-sky-100 transition"
                                            data-copy="{{ $DANA_NUMBER }}">Salin</button>
                                    </div>
                                </label>

                                {{-- BCA --}}
                                <label for="transfer-bca" class="cursor-pointer">
                                    <input type="radio" id="transfer-bca" name="transfer_method" value="bca" class="peer sr-only">
                                    <div class="rounded-xl ring-1 ring-sky-200 bg-white p-4 h-full
                              peer-checked:ring-2 peer-checked:ring-sky-400 peer-checked:bg-sky-50 transition">
                                        <div class="flex items-center gap-2 mb-2">
                                            <div class="h-8 w-8 rounded-lg grid place-items-center text-indigo-700"
                                                style="background:linear-gradient(135deg,#e2e7ff,#ccd6ff); box-shadow:inset 0 0 0 2px #fff;">
                                                <i class="fa-solid fa-building-columns"></i>
                                            </div>
                                            <span class="font-semibold text-slate-900">BCA</span>
                                        </div>
                                        <p class="text-sm text-slate-600 leading-5">
                                            Rek. BCA: <strong class="text-slate-900" id="bcaNumber">{{ $BCA_NUMBER }}</strong><br>
                                            a.n. {{ $BCA_NAME }}
                                        </p>
                                        <button type="button" class="mt-2 text-xs font-semibold px-3 py-1.5 rounded-lg bg-sky-50 text-sky-700 ring-1 ring-sky-200 hover:bg-sky-100 transition"
                                            data-copy="{{ $BCA_NUMBER }}">Salin</button>
                                    </div>
                                </label>
                            </div>
                        </div>

                        {{-- Upload Bukti Pembayaran (muncul jika full/down_payment) --}}
                        <div id="paymentProofSection" class="hidden">
                            <label for="paymentProofInput" class="block text-xs font-bold tracking-wide text-slate-700 mb-2 uppercase">
                                Unggah Bukti Pembayaran *
                            </label>
                            <input type="file" name="payment_proof" id="paymentProofInput"
                                class="w-full text-sm text-slate-700
                       file:mr-4 file:py-2.5 file:px-4
                       file:rounded-xl file:border-0 file:font-semibold
                       file:bg-sky-50 file:text-sky-700
                       hover:file:bg-sky-100 cursor-pointer">
                            <p class="text-xs text-slate-500 mt-1">Wajib diisi untuk Bayar Lunas atau Uang Muka.</p>
                            @error('payment_proof') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-sky-50/60 border-t border-sky-100 sm:flex sm:flex-row-reverse gap-2">
                        <button type="submit"
                            class="w-full sm:w-auto inline-flex justify-center rounded-xl shadow-md hover:shadow-lg px-5 py-2.5 text-white font-semibold transition"
                            style="background:linear-gradient(90deg,#3b82f6,#06b6d4);">
                            Konfirmasi & Pesan Sekarang
                        </button>
                        <button type="button" id="closeBookingModalBtn"
                            class="mt-3 sm:mt-0 w-full sm:w-auto inline-flex justify-center rounded-xl border border-sky-200 px-5 py-2.5 bg-white text-slate-700 font-semibold hover:bg-sky-50 transition">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    // ===== Perhitungan Total =====
    function calculateTotalPrice() {
        let total = 0;
        const checkboxes = document.querySelectorAll('.service-checkbox:checked');
        checkboxes.forEach(cb => {
            const serviceId = cb.value;
            const price = parseFloat(cb.dataset.price);
            const quantityInput = document.querySelector(`.quantity-input[data-service="${serviceId}"]`);
            const quantity = parseInt(quantityInput.value) || 1;
            total += price * quantity;
        });
        return total;
    }

    function formatNumber(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    document.addEventListener('DOMContentLoaded', function() {
        const openModalBtn = document.getElementById('openBookingModalBtn');
        const closeModalBtn = document.getElementById('closeBookingModalBtn');
        const modal = document.getElementById('bookingModal');
        const modalTotalPriceEl = document.getElementById('modalTotalPrice');

        const modalPaymentTypeEl = document.getElementById('modalPaymentType');
        const paymentProofSectionEl = document.getElementById('paymentProofSection');
        const paymentProofInputEl = document.getElementById('paymentProofInput');

        // Transfer
        const transferMethodSectionEl = document.getElementById('transferMethodSection');
        const transferRadios = document.querySelectorAll('input[name="transfer_method"]');

        const pageTotalPriceEl = document.getElementById('pageTotalPrice');
        const pageSelectedCountEl = document.getElementById('pageSelectedCount');

        const checkboxes = document.querySelectorAll('.service-checkbox');
        const quantityInputs = document.querySelectorAll('.quantity-input');

        // ===== Style kartu + toggle quantity ketika checkbox berubah =====
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function(ev) {
                const serviceId = this.value;
                const quantityInput = document.querySelector(`.quantity-input[data-service="${serviceId}"]`);
                const card = this.closest('._card'); // pastikan wrapper punya class "_card"

                if (this.checked) {
                    quantityInput.disabled = false;
                    card.classList.add('ring-2', 'ring-sky-300', 'bg-sky-50');
                    card.classList.remove('bg-white');
                } else {
                    quantityInput.disabled = true;
                    quantityInput.value = 1;
                    card.classList.remove('ring-2', 'ring-sky-300', 'bg-sky-50');
                    card.classList.add('bg-white');
                }
                // Update ringkasan halaman
                const total = calculateTotalPrice();
                pageTotalPriceEl.textContent = 'Rp ' + formatNumber(total);
                pageSelectedCountEl.textContent = document.querySelectorAll('.service-checkbox:checked').length + ' layanan dipilih';
            });

            // Klik kartu = toggle checkbox (hindari jika klik di kontrol interaktif)
            const card = checkbox.closest('._card');
            if (card) {
                card.addEventListener('click', (ev) => {
                    const isInteractive = ev.target === checkbox || ev.target.classList.contains('quantity-input') || ev.target.closest('.quantity-input');
                    if (isInteractive) return;
                    checkbox.checked = !checkbox.checked;
                    checkbox.dispatchEvent(new Event('change'));
                });
            }
        });

        // Ubah quantity → update total
        quantityInputs.forEach(input => {
            input.addEventListener('input', () => {
                const val = Math.max(1, parseInt(input.value) || 1);
                input.value = val;
                const total = calculateTotalPrice();
                pageTotalPriceEl.textContent = 'Rp ' + formatNumber(total);
                pageSelectedCountEl.textContent = document.querySelectorAll('.service-checkbox:checked').length + ' layanan dipilih';
            });
        });

        // ===== Open Modal =====
        openModalBtn.addEventListener('click', function() {
            const selectedServicesCount = document.querySelectorAll('.service-checkbox:checked').length;
            if (selectedServicesCount === 0) {
                alert('Harap pilih minimal satu layanan sebelum melanjutkan.');
                return;
            }
            const total = calculateTotalPrice();
            modalTotalPriceEl.textContent = 'Rp ' + formatNumber(total);
            modal.classList.remove('hidden');
            togglePaymentProof(); // set tampilan sesuai pilihan saat modal dibuka
        });

        // ===== Close Modal =====
        closeModalBtn.addEventListener('click', function() {
            modal.classList.add('hidden');
        });

        // ===== Tipe Pembayaran → tampilkan bukti bayar & metode transfer bila perlu =====
        function togglePaymentProof() {
            const method = modalPaymentTypeEl.value;
            const needTransfer = (method === 'full' || method === 'down_payment');

            if (needTransfer) {
                // tampilkan metode transfer + bukti bayar
                transferMethodSectionEl.classList.remove('hidden');
                paymentProofSectionEl.classList.remove('hidden');
                transferRadios.forEach(r => r.required = true);
                if (paymentProofInputEl) paymentProofInputEl.required = true;
            } else {
                // sembunyikan dan reset pilihan
                transferMethodSectionEl.classList.add('hidden');
                paymentProofSectionEl.classList.add('hidden');
                transferRadios.forEach(r => {
                    r.required = false;
                    r.checked = false;
                });
                if (paymentProofInputEl) paymentProofInputEl.required = false;
            }
        }
        modalPaymentTypeEl.addEventListener('change', togglePaymentProof);

        // ===== Tombol "Salin" nomor rekening =====
        document.querySelectorAll('[data-copy]').forEach(btn => {
            btn.addEventListener('click', async () => {
                try {
                    await navigator.clipboard.writeText(btn.getAttribute('data-copy'));
                    const old = btn.textContent;
                    btn.textContent = 'Tersalin!';
                    setTimeout(() => btn.textContent = old, 1200);
                } catch (e) {
                    alert('Gagal menyalin nomor. Salin manual: ' + btn.getAttribute('data-copy'));
                }
            });
        });

        // ===== Minimal Tanggal Sekarang =====
        const now = new Date();
        now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
        const minDateTime = now.toISOString().slice(0, 16);
        const bookingDateEl = document.getElementById('booking_date');
        bookingDateEl.min = minDateTime;
        if (!bookingDateEl.value) bookingDateEl.value = minDateTime;

        // Inisialisasi ringkasan awal
        pageTotalPriceEl.textContent = 'Rp ' + formatNumber(calculateTotalPrice());
        pageSelectedCountEl.textContent = document.querySelectorAll('.service-checkbox:checked').length + ' layanan dipilih';
    });
</script>
@endpush