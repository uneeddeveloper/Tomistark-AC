<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class TechnicianDashboardController extends Controller
{
    /**
     * Dashboard teknisi
     */
    public function index(Request $request)
    {
        $tech = Auth::user();
        abort_unless($tech && $tech->role === 'technician', 403, 'Hanya teknisi yang dapat mengakses dashboard ini.');

        // ==== STATS ====
        $today = Carbon::today();
        $monthStart = Carbon::now()->startOfMonth();

        $tasksToday = Booking::where('technician_id', $tech->id)
            ->whereDate('booking_date', $today)
            ->whereIn('status', ['assigned', 'confirmed', 'in_progress'])
            ->count();

        $activeJobs = Booking::where('technician_id', $tech->id)
            ->whereIn('status', ['assigned', 'confirmed', 'in_progress'])
            ->count();

        $completedThisMonth = Booking::where('technician_id', $tech->id)
            ->where('status', 'completed')
            ->where('updated_at', '>=', $monthStart)
            ->count();

        $confirmedRevenue = Payment::whereHas('booking', function ($q) use ($tech) {
            $q->where('technician_id', $tech->id);
        })
            ->where('status', 'paid')
            ->sum('amount');

        $stats = [
            'tasks_today'          => $tasksToday,
            'active_jobs'          => $activeJobs,
            'completed_this_month' => $completedThisMonth,
            'confirmed_revenue'    => $confirmedRevenue,
        ];

        // ==== CHART (30 hari) ====
        $start = Carbon::now()->subDays(29)->startOfDay();
        $end   = Carbon::now()->endOfDay();
        $period = CarbonPeriod::create($start, '1 day', $end);

        $labels  = [];
        $jobs    = [];
        $revenue = [];

        foreach ($period as $date) {
            $labels[] = $date->format('d M');

            // jobs = jumlah booking completed pada tanggal tsb (pakai updated_at sebagai waktu selesai)
            $jobs[] = Booking::where('technician_id', $tech->id)
                ->where('status', 'completed')
                ->whereDate('updated_at', $date->toDateString())
                ->count();

            // revenue = total paid pada tanggal tsb (paid_at)
            $revenue[] = (int) Payment::whereHas('booking', function ($q) use ($tech) {
                $q->where('technician_id', $tech->id);
            })
                ->where('status', 'paid')
                ->whereDate('paid_at', $date->toDateString())
                ->sum('amount');
        }

        $chart = [
            'labels'  => $labels,
            'jobs'    => $jobs,
            'revenue' => $revenue,
        ];

        // ==== LIST TUGAS AKTIF ====
        $active_list = Booking::with(['user', 'services', 'payment'])
            ->where('technician_id', $tech->id)
            ->whereIn('status', ['assigned', 'confirmed', 'in_progress'])
            ->orderBy('booking_date', 'asc')
            ->limit(10)
            ->get();

        // ==== COD PENDING (butuh tindakan / upload bukti) ====
        $cod_pending = Payment::with(['booking.user'])
            ->whereHas('booking', function ($q) use ($tech) {
                $q->where('technician_id', $tech->id);
            })
            ->whereIn(DB::raw('LOWER(payment_method)'), ['cod', 'cash', 'cash_on_delivery', 'tunai'])
            ->whereIn('status', ['pending', 'pending_verification'])
            ->orderBy('updated_at', 'desc')
            ->limit(8)
            ->get();

        return view('technician.dashboard', compact('stats', 'chart', 'active_list', 'cod_pending'));
    }

    /**
     * Halaman daftar COD (opsional/komplemen)
     */
    public function cod(Request $request)
    {
        $tech = Auth::user();
        abort_unless($tech && $tech->role === 'technician', 403);

        $cod_list = Payment::with(['booking.user'])
            ->whereHas('booking', function ($q) use ($tech) {
                $q->where('technician_id', $tech->id);
            })
            ->whereIn(DB::raw('LOWER(payment_method)'), ['cod', 'cash', 'cash_on_delivery', 'tunai'])
            ->orderBy('updated_at', 'desc')
            ->paginate(12);

        return view('technician.cod', compact('cod_list'));
    }

    /**
     * Setor COD (wajib bukti jika metode cod/tunai)
     */
    public function collectCod(Request $request, Payment $payment)
    {
        $user = Auth::user();
        abort_unless($user && $user->role === 'technician', 403, 'Hanya teknisi yang dapat menyetor COD.');
        abort_unless($payment->booking && (int)$payment->booking->technician_id === (int)$user->id, 403, 'Pembayaran ini bukan milik tugas Anda.');

        $method = strtolower($payment->payment_method ?? '');
        $isCOD  = in_array($method, ['cod', 'cash', 'cash_on_delivery', 'tunai'], true);

        $rules = [
            'cod_received_amount' => ['required', 'numeric', 'min:0'],
        ];
        // Bukti wajib kalau COD
        if ($isCOD) {
            $rules['cod_proof'] = ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'];
        } else {
            $rules['cod_proof'] = ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'];
        }

        $data = $request->validate($rules);

        // Simpan bukti
        $path = $payment->payment_proof;
        if ($request->hasFile('cod_proof')) {
            if (!empty($payment->payment_proof)) {
                Storage::disk('public')->delete($payment->payment_proof);
            }
            $path = $request->file('cod_proof')->store('payments/cod', 'public');
        }

        // Update payment ke antrian verifikasi admin
        $payment->amount              = $data['cod_received_amount']; // jika ingin lock ke total tagihan, hapus baris ini
        $payment->payment_proof       = $path;
        $payment->status              = 'pending_verification';
        $payment->verification_status = 'pending';
        $payment->save();

        return back()->with('success', 'Setoran COD berhasil dikirim & menunggu verifikasi admin.');
    }

    /**
     * Update status pekerjaan (teknisi)
     * - assigned/confirmed -> in_progress
     * - in_progress        -> completed
     * - Jika COD/tunai: sebelum completed, WAJIB ada payment_proof
     */
    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'next_status' => 'required|in:in_progress,completed',
        ]);

        $user = Auth::user();
        abort_unless($user && $user->role === 'technician', 403, 'Hanya teknisi yang boleh mengubah status.');
        abort_unless((int)$booking->technician_id === (int)$user->id, 403, 'Tugas ini bukan milik Anda.');

        $current = $booking->status;
        $next    = $request->input('next_status');

        $allowed = [
            'assigned'    => ['in_progress'],
            'confirmed'   => ['in_progress'],
            'in_progress' => ['completed'],
        ];

        if (!isset($allowed[$current]) || !in_array($next, $allowed[$current], true)) {
            return back()->with('error', "Transisi status tidak diizinkan dari '{$current}' ke '{$next}'.");
        }

        // Wajib bukti COD sebelum completed
        if ($next === 'completed') {
            $payment = $booking->payment;
            $method  = strtolower($payment->payment_method ?? '');
            $isCOD   = in_array($method, ['cod', 'cash', 'cash_on_delivery', 'tunai'], true);

            if ($isCOD && empty($payment->payment_proof)) {
                return back()->with('error', 'Wajib setor & unggah bukti pembayaran COD terlebih dahulu sebelum menandai selesai.');
            }
        }

        $booking->status = $next;
        $booking->save();

        return back()->with('success', 'Status tugas diperbarui menjadi ' . str_replace('_', ' ', $next) . '.');
    }
}
