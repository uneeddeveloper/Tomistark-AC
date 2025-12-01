@extends('admin.layout')

@section('title', 'Permintaan Refund')
@section('header', 'Permintaan Refund')
@section('subheader', 'Setujui atau tolak permintaan refund dari pelanggan')

@section('content')

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <div class="bg-gradient-to-r from-orange-500 to-red-500 rounded-2xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-orange-100 text-sm font-medium">Menunggu Keputusan</p>
                <p class="text-3xl font-bold mt-1">{{ $pendingRefundCount }}</p>
            </div>
            <div class="bg-red-400 p-3 rounded-xl bg-opacity-20">
                <i class="fas fa-clock text-2xl"></i>
            </div>
        </div>
    </div>
    <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-2xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100 text-sm font-medium">Disetujui Hari Ini</p>
                <p class="text-3xl font-bold mt-1">{{ $approvedTodayCount }}</p>
            </div>
            <div class="bg-green-400 p-3 rounded-xl bg-opacity-20">
                <i class="fas fa-check-circle text-2xl"></i>
            </div>
        </div>
    </div>
    <div class="bg-gradient-to-r from-gray-600 to-gray-800 rounded-2xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-100 text-sm font-medium">Total Telah Di-refund</p>
                <p class="text-3xl font-bold mt-1">Rp {{ number_format($totalRefunded ?? 0, 0, ',', '.') }}</p>
            </div>
            <div class="bg-gray-400 p-3 rounded-xl bg-opacity-20">
                <i class="fas fa-undo text-2xl"></i>
            </div>
        </div>
    </div>
</div>

<h3 class="text-xl font-semibold text-gray-900 mb-4">Antrian Permintaan Refund</h3>
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    @forelse($payments as $payment)
    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden flex flex-col">

        <div class="p-5 border-b border-gray-200 bg-gray-50">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-base font-semibold text-gray-900">
                        {{ $payment->booking->user->name ?? 'N/A' }}
                    </p>
                    <p class="text-sm text-gray-600">
                        Booking #{{ $payment->booking_id }}
                    </p>
                </div>
                <p class="text-xs text-gray-500">{{ $payment->updated_at->format('d M Y, H:i') }}</p>
            </div>
        </div>

        <div class="p-5 flex-grow space-y-4">
            <div>
                <p class="text-sm text-gray-500">Jumlah Diajukan</p>
                <p class="text-2xl font-bold text-red-600">
                    Rp {{ number_format($payment->refund_amount, 0, ',', '.') }}
                </p>
            </div>
            <div class="border-t pt-4">
                <p class="text-sm font-medium text-gray-800 mb-2">Alasan Pelanggan:</p>
                <p class="text-sm text-gray-600 italic bg-gray-100 p-3 rounded-md border">
                    "{{ $payment->refund_reason }}"
                </p>
            </div>
        </div>

        <div class="bg-gray-50 p-4 border-t border-gray-200 grid grid-cols-2 gap-3">
            <button onclick="showRefundActionModal({{ $payment->id }}, 'reject')"
                class="w-full px-4 py-2 bg-white border border-gray-300 text-red-600 rounded-lg hover:bg-red-50 font-medium transition-all duration-200">
                <i class="fas fa-times mr-2"></i>Tolak
            </button>
            <button onclick="showRefundActionModal({{ $payment->id }}, 'approve')"
                class="w-full px-4 py-2 bg-green-600 border border-transparent text-white rounded-lg hover:bg-green-700 font-medium shadow-sm transition-all duration-200">
                <i class="fas fa-check mr-2"></i>Setujui
            </button>
        </div>
    </div>
    @empty
    <div class="lg:col-span-2 text-center bg-white rounded-xl shadow p-12 border border-gray-100">
        <i class="fas fa-check-double text-green-400 text-6xl mb-4"></i>
        <h3 class="text-xl font-semibold text-gray-800">Tidak Ada Permintaan Refund</h3>
        <p class="text-gray-500 mt-2">Saat ini tidak ada permintaan refund yang menunggu keputusan Anda.</p>
    </div>
    @endforelse
</div>

@if($payments->hasPages())
<div class="mt-8">
    {{ $payments->links() }}
</div>
@endif


<div id="refundActionModal" class="fixed z-50 inset-0 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true" @click="closeRefundActionModal()">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form id="refundActionForm" method="POST"> {{-- Action akan di-set oleh JS --}}
                @csrf
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="refundModalTitle">Konfirmasi Aksi</h3>
                    <input type="hidden" id="refundPaymentId">

                    <div class="mt-4 hidden" id="rejectReasonSection">
                        <label for="admin_notes" class="block text-sm font-medium text-gray-700">Alasan Penolakan *</label>
                        <textarea name="admin_notes" id="admin_notes" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500" placeholder="Jelaskan mengapa permintaan ini ditolak..."></textarea>
                    </div>
                    <div class="mt-4 hidden" id="approveNotesSection">
                        <label for="admin_notes_approve" class="block text-sm font-medium text-gray-700">Catatan (Opsional)</label>
                        <textarea name="admin_notes" id="admin_notes_approve" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500" placeholder="Catatan internal..."></textarea>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" id="confirmRefundButton" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 text-base font-medium text-white sm:ml-3 sm:w-auto sm:text-sm">
                        Konfirmasi
                    </button>
                    <button type="button" onclick="closeRefundActionModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const modal = document.getElementById('refundActionModal');
    const form = document.getElementById('refundActionForm');
    const modalTitle = document.getElementById('refundModalTitle');
    const confirmButton = document.getElementById('confirmRefundButton');
    const rejectReasonSection = document.getElementById('rejectReasonSection');
    const rejectNotesInput = document.getElementById('admin_notes');
    const approveNotesSection = document.getElementById('approveNotesSection');
    const approveNotesInput = document.getElementById('admin_notes_approve');

    function showRefundActionModal(paymentId, action) {
        // Set URL Form
        if (action === 'approve') {
            form.action = `/admin/refunds/${paymentId}/approve`;
            modalTitle.innerText = 'Setujui Permintaan Refund?';
            confirmButton.className = 'w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 sm:ml-3 sm:w-auto sm:text-sm';

            rejectReasonSection.classList.add('hidden');
            rejectNotesInput.required = false;

            approveNotesSection.classList.remove('hidden');
            approveNotesInput.name = "admin_notes"; // Aktifkan input ini
            rejectNotesInput.name = ""; // Nonaktifkan input reject

        } else { // action === 'reject'
            form.action = `/admin/refunds/${paymentId}/reject`;
            modalTitle.innerText = 'Tolak Permintaan Refund?';
            confirmButton.className = 'w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 sm:ml-3 sm:w-auto sm:text-sm';

            rejectReasonSection.classList.remove('hidden');
            rejectNotesInput.required = true;

            approveNotesSection.classList.add('hidden');
            approveNotesInput.name = ""; // Nonaktifkan input approve
            rejectNotesInput.name = "admin_notes"; // Aktifkan input ini
        }

        modal.classList.remove('hidden');
    }

    function closeRefundActionModal() {
        modal.classList.add('hidden');
        // Reset form
        rejectNotesInput.value = '';
        approveNotesInput.value = '';
    }

    // AJAX Form Submission
    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        const submitBtn = document.getElementById('confirmRefundButton');
        const originalBtnText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';

        try {
            const formData = new FormData(this);
            const response = await fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });

            const result = await response.json();

            if (response.ok && result.success) {
                closeRefundActionModal();
                alert(result.message); // Ganti dengan notifikasi yang lebih baik
                window.location.reload();
            } else {
                if (response.status === 422) {
                    let errorMsg = result.message || 'Kesalahan validasi.';
                    if (result.errors) {
                        errorMsg = Object.values(result.errors)[0][0];
                    }
                    throw new Error(errorMsg);
                }
                throw new Error(result.message || 'Terjadi kesalahan');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Gagal memproses: ' + error.message);
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnText;
        }
    });
</script>
@endpush