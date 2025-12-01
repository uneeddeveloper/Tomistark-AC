@extends('layouts.user')

@section('title', 'Payment Details - ServisAC')

@section('content')
<div class="max-w-4xl mx-auto">

    {{-- Kartu Utama --}}
    <div class="relative rounded-2xl bg-white/85 backdrop-blur border border-sky-100 shadow-sm overflow-hidden">
        {{-- Aksen garis atas --}}
        <div class="absolute inset-x-0 top-0 h-1" style="background:linear-gradient(90deg,#22d3ee,#2563eb)"></div>

        {{-- Header --}}
        <div class="p-6 border-b border-sky-100">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h2 class="text-2xl font-extrabold text-slate-900">Payment Details</h2>
                    <p class="text-slate-600">Payment #{{ $payment->id }}</p>
                </div>

                <div class="text-right space-y-2">
                    {{-- Status utama --}}
                    <span class="px-3 py-1 rounded-full text-sm font-bold ring-1 whitespace-nowrap
            @if($payment->status=='paid') bg-emerald-50 text-emerald-700 ring-emerald-200
            @elseif($payment->status=='pending') bg-amber-50 text-amber-700 ring-amber-200
            @elseif($payment->status=='pending_verification') bg-sky-50 text-sky-700 ring-sky-200
            @elseif($payment->status=='failed') bg-rose-50 text-rose-700 ring-rose-200
            @elseif($payment->status=='refunded') bg-slate-50 text-slate-700 ring-slate-200
            @else bg-slate-50 text-slate-700 ring-slate-200 @endif">
                        {{ ucfirst(str_replace('_', ' ', $payment->status)) }}
                    </span>

                    {{-- Status verifikasi --}}
                    @if($payment->verification_status == 'pending')
                    <span class="block px-3 py-1 rounded-full text-xs font-bold bg-orange-50 text-orange-700 ring-1 ring-orange-200">
                        Waiting Verification
                    </span>
                    @elseif($payment->verification_status == 'approved')
                    <span class="block px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200">
                        Verified
                    </span>
                    @elseif($payment->verification_status == 'rejected')
                    <span class="block px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 ring-1 ring-rose-200">
                        Rejected
                    </span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Body --}}
        <div class="p-6 space-y-8">

            {{-- Grid Info --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Kolom: Payment Information --}}
                <div>
                    <h3 class="text-lg font-extrabold text-slate-900 mb-4 flex items-center gap-3">
                        <span class="h-9 w-9 rounded-lg grid place-items-center text-sky-700"
                            style="background:linear-gradient(135deg,#dff4ff,#bde7ff);box-shadow:inset 0 0 0 2px #fff;">
                            <i class="fas fa-credit-card"></i>
                        </span>
                        Payment Information
                    </h3>

                    <div class="space-y-3 rounded-xl border border-sky-100 bg-white/70 p-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-600">Payment Type</label>
                            <p class="mt-1 text-slate-900 capitalize">
                                @if($payment->payment_type === 'full')
                                Full Payment
                                @elseif($payment->payment_type === 'down_payment')
                                Down Payment (50%)
                                @else
                                Cash on Delivery
                                @endif
                            </p>
                        </div>

                        @if($payment->payment_type === 'down_payment')
                        <div>
                            <label class="block text-sm font-medium text-slate-600">Down Payment</label>
                            <p class="mt-1 text-xl font-extrabold bg-clip-text text-transparent"
                                style="background-image:linear-gradient(90deg,#2563eb,#06b6d4);">
                                Rp {{ number_format($payment->down_payment_amount, 0, ',', '.') }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-600">Remaining</label>
                            <p class="mt-1 text-lg font-semibold text-slate-900">
                                Rp {{ number_format($payment->remaining_amount, 0, ',', '.') }}
                            </p>
                        </div>
                        @else
                        <div>
                            <label class="block text-sm font-medium text-slate-600">Total Amount</label>
                            <p class="mt-1 text-2xl font-extrabold bg-clip-text text-transparent"
                                style="background-image:linear-gradient(90deg,#2563eb,#06b6d4);">
                                Rp {{ number_format($payment->amount, 0, ',', '.') }}
                            </p>
                        </div>
                        @endif

                        <div>
                            <label class="block text-sm font-medium text-slate-600">Payment Method</label>
                            <p class="mt-1 text-slate-900 capitalize">{{ $payment->payment_method ?? 'Not specified' }}</p>
                        </div>

                        @if($payment->transaction_id)
                        <div>
                            <label class="block text-sm font-medium text-slate-600">Transaction ID</label>
                            <p class="mt-1 text-slate-900">{{ $payment->transaction_id }}</p>
                        </div>
                        @endif

                        @if($payment->paid_at)
                        <div>
                            <label class="block text-sm font-medium text-slate-600">Payment Date</label>
                            <p class="mt-1 text-slate-900">{{ $payment->paid_at->format('F d, Y H:i') }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Kolom: Service Information --}}
                <div>
                    <h3 class="text-lg font-extrabold text-slate-900 mb-4 flex items-center gap-3">
                        <span class="h-9 w-9 rounded-lg grid place-items-center text-indigo-700"
                            style="background:linear-gradient(135deg,#e2e7ff,#ccd6ff);box-shadow:inset 0 0 0 2px #fff;">
                            <i class="fas fa-tools"></i>
                        </span>
                        Service Information
                    </h3>

                    <div class="space-y-3 rounded-xl border border-sky-100 bg-white/70 p-4">
                        @if($payment->booking)
                        <div>
                            <label class="block text-sm font-medium text-slate-600">Booking ID</label>
                            <p class="mt-1 text-slate-900">#{{ $payment->booking->id }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-600">Services</label>
                            <div class="mt-1">
                                @foreach($payment->booking->services as $service)
                                <span class="inline-flex items-center gap-1 bg-sky-50 text-sky-800 ring-1 ring-sky-200 px-2 py-1 rounded text-xs mr-1 mb-1">
                                    <i class="fas fa-snowflake"></i> {{ $service->name }} ({{ $service->pivot->quantity }})
                                </span>
                                @endforeach
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-600">Booking Date</label>
                            <p class="mt-1 text-slate-900">{{ $payment->booking->booking_date->format('F d, Y H:i') }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-600">Service Address</label>
                            <p class="mt-1 text-slate-900 leading-relaxed">{{ $payment->booking->address }}</p>
                        </div>

                        @elseif($payment->serviceRequest)
                        <div>
                            <label class="block text-sm font-medium text-slate-600">Service Type</label>
                            <p class="mt-1 text-slate-900">{{ $payment->serviceRequest->service_type }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-600">Description</label>
                            <p class="mt-1 text-slate-900">{{ $payment->serviceRequest->description }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Admin Notes --}}
            @if($payment->admin_notes)
            <div>
                <h3 class="text-lg font-extrabold text-slate-900 mb-3">Admin Notes</h3>
                <div class="rounded-xl border border-sky-100 bg-sky-50/60 p-4">
                    <p class="text-slate-800">{{ $payment->admin_notes }}</p>
                </div>
            </div>
            @endif

            {{-- Payment Instructions --}}
            @if($payment->status == 'pending' && !$payment->payment_method)
            <div>
                <h3 class="text-lg font-extrabold text-slate-900 mb-4">Complete Your Payment</h3>

                <div class="rounded-xl border border-amber-200 bg-amber-50 p-4 mb-4">
                    <p class="text-amber-800 font-medium">
                        Please select a payment method and upload the payment proof if required.
                    </p>
                </div>

                <form action="{{ route('user.payments.process', $payment->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Payment Method *</label>
                        <select name="payment_method" id="paymentMethodSelect" required onchange="togglePaymentProof()"
                            class="w-full px-3 py-2 rounded-xl border border-sky-100 bg-white focus:ring-4 focus:ring-sky-200/60 focus:border-sky-300">
                            <option value="">Select Payment Method</option>
                            <option value="cash">Cash on Delivery (Pay to technician)</option>
                            <option value="transfer">Bank Transfer</option>
                            <option value="qris">QRIS</option>
                        </select>
                    </div>

                    <div id="paymentProofSection" class="hidden">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Payment Proof *</label>
                        <input type="file" name="payment_proof" accept="image/*"
                            class="w-full px-3 py-2 rounded-xl border border-sky-100 bg-white focus:ring-4 focus:ring-sky-200/60 focus:border-sky-300">
                        <p class="text-xs text-slate-500 mt-1">Upload screenshot of transfer confirmation or QRIS payment.</p>
                    </div>

                    <div id="bankInfo" class="hidden rounded-xl border border-sky-100 bg-sky-50/60 p-4">
                        <p class="font-semibold text-slate-900 mb-2">Bank Transfer Details:</p>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-sm">
                            <p class="text-slate-700">Bank: <span class="font-medium">BCA</span></p>
                            <p class="text-slate-700">Account: <span id="rekNo" class="font-semibold">123-456-7890</span></p>
                            <p class="text-slate-700">Name: <span class="font-medium">PT. ServisAC Indonesia</span></p>
                        </div>
                        <button type="button" id="copyRekBtn"
                            class="mt-3 inline-flex items-center gap-2 px-3 py-2 rounded-lg font-semibold text-sky-700 ring-1 ring-sky-200 bg-white hover:bg-sky-50 transition">
                            <i class="fas fa-copy"></i> Copy Account Number
                        </button>
                    </div>

                    <button type="submit"
                        class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl font-semibold text-white shadow-sm hover:shadow-md transition"
                        style="background:linear-gradient(90deg,#3b82f6,#06b6d4);">
                        <i class="fas fa-paper-plane"></i> Submit Payment
                    </button>
                </form>
            </div>

            @elseif($payment->status == 'pending_verification')
            <div>
                <div class="rounded-xl border border-sky-100 bg-sky-50/60 p-4">
                    <div class="flex items-start gap-3">
                        <span class="h-10 w-10 rounded-xl grid place-items-center text-sky-700"
                            style="background:linear-gradient(135deg,#dff4ff,#bde7ff);box-shadow:inset 0 0 0 2px #fff;">
                            <i class="fas fa-info-circle"></i>
                        </span>
                        <div>
                            <p class="text-sky-900 font-semibold">Payment Submitted</p>
                            <p class="text-sky-700 text-sm mt-1">Your payment is being verified by admin. This usually takes 1-2 hours.</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- Actions --}}
            <div class="pt-2">
                <a href="{{ route('user.payments') }}"
                    class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl font-semibold
                  ring-1 ring-sky-200 text-slate-700 bg-white hover:bg-sky-50 transition">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Script kecil untuk interaksi --}}
<script>
    function togglePaymentProof() {
        const method = document.getElementById('paymentMethodSelect').value;
        const proofSection = document.getElementById('paymentProofSection');
        const bankInfo = document.getElementById('bankInfo');

        if (method === 'transfer') {
            proofSection.classList.remove('hidden');
            bankInfo.classList.remove('hidden');
            proofSection.querySelector('input').required = true;
        } else if (method === 'qris') {
            proofSection.classList.remove('hidden');
            bankInfo.classList.add('hidden');
            proofSection.querySelector('input').required = true;
        } else {
            proofSection.classList.add('hidden');
            bankInfo.classList.add('hidden');
            proofSection.querySelector('input').required = false;
        }
    }

    // Copy nomor rekening
    document.addEventListener('DOMContentLoaded', () => {
        const copyBtn = document.getElementById('copyRekBtn');
        if (copyBtn) {
            copyBtn.addEventListener('click', () => {
                const no = document.getElementById('rekNo')?.innerText?.replaceAll('-', '') || '';
                navigator.clipboard.writeText(no).then(() => {
                    copyBtn.innerHTML = '<i class="fas fa-check"></i> Copied';
                    setTimeout(() => copyBtn.innerHTML = '<i class="fas fa-copy"></i> Copy Account Number', 2000);
                });
            });
        }
    });
</script>
@endsection