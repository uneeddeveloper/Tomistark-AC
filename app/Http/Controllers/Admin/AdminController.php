<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Service;
use App\Models\Rating;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Display admin dashboard
     */
    public function dashboard()
    {
        $stats = [
            'total_users'           => User::where('role', 'user')->count(),
            'total_bookings'        => Booking::count(),
            'pending_bookings'      => Booking::where('status', 'pending')->count(),
            'completed_bookings'    => Booking::where('status', 'completed')->count(),
            'total_revenue'         => Payment::where('status', 'paid')->sum('amount'),
            'pending_verification'  => Payment::where('status', 'pending_verification')->count(),
        ];

        $recent_bookings = Booking::with(['user', 'services', 'payment'])
            ->latest('created_at')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_bookings'));
    }

    /**
     * Helper: percentage change
     */
    private function calculatePercentageChange($current, $previous)
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }
        return (($current - $previous) / $previous) * 100;
    }

    /**
     * USERS
     */
    public function users(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $s = $request->input('search');
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                    ->orWhere('email', 'like', "%{$s}%")
                    ->orWhere('phone_number', 'like', "%{$s}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->input('role'));
        }

        $sort  = $request->input('sort', 'created_at');
        $order = $request->input('order', 'desc');
        $users = $query->orderBy($sort, $order)->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    public function createUser()
    {
        return view('admin.users.create');
    }

    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|string|email|max:255|unique:users',
            'password'     => 'required|string|min:8|confirmed',
            'role'         => 'required|in:user,admin,technician',
            'phone_number' => 'nullable|string|max:20',
            'address'      => 'nullable|string|max:500',
        ]);

        User::create([
            'name'         => $validated['name'],
            'email'        => $validated['email'],
            'password'     => Hash::make($validated['password']),
            'role'         => $validated['role'],
            'phone_number' => $validated['phone_number'] ?? null,
            'address'      => $validated['address'] ?? null,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role'         => 'required|in:user,admin,technician',
            'phone_number' => 'nullable|string|max:20',
            'address'      => 'nullable|string|max:500',
            'password'     => 'nullable|string|min:8|confirmed',
        ]);

        $user->name         = $validated['name'];
        $user->email        = $validated['email'];
        $user->role         = $validated['role'];
        $user->phone_number = $validated['phone_number'] ?? null;
        $user->address      = $validated['address'] ?? null;

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function deleteUser(User $user)
    {
        try {
            if ($user->id == Auth::id()) {
                return redirect()->route('admin.users.index')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
            }
            $user->delete();
            return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.users.index')->with('error', 'Error deleting user: ' . $e->getMessage());
        }
    }

    public function showUser(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    /**
     * BOOKINGS
     */
    public function bookings(Request $request)
    {
        $query = Booking::with(['user', 'technician', 'services', 'payment'])
            ->latest('created_at');

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('search')) {
            $s = $request->input('search');
            $query->whereHas('user', fn($q) => $q->where('name', 'like', "%{$s}%"));
        }

        $bookings    = $query->paginate(10);
        $technicians = User::isTechnician()->get();

        return view('admin.bookings.index', compact('bookings', 'technicians'));
    }

    public function showBooking($id)
    {
        $booking     = Booking::with(['user', 'technician', 'services', 'payment'])->findOrFail($id);
        $technicians = User::isTechnician()->get();

        return view('admin.bookings.show', compact('booking', 'technicians'));
    }

    public function approvePendingBooking(Request $request, $id)
    {
        $validated = $request->validate([
            'technician_id' => 'required|exists:users,id'
        ]);

        $booking = Booking::with('payment')->findOrFail($id);

        if ($booking->status !== 'pending') {
            return back()->with('error', 'Booking ini tidak bisa disetujui (Status: ' . $booking->status . ').');
        }
        if (!$booking->payment) {
            return back()->with('error', 'Booking ini tidak memiliki data pembayaran.');
        }

        try {
            DB::beginTransaction();

            $booking->update([
                'status'        => 'confirmed',
                'technician_id' => $validated['technician_id']
            ]);

            // payment menjadi pending (menunggu pembayaran user/teknisi)
            $booking->payment->update(['status' => 'pending']);

            DB::commit();
            return redirect()->route('admin.bookings.show', $booking->id)
                ->with('success', 'Booking approved and technician assigned. User can now pay.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal approve booking #' . $id . ': ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function rejectPendingBooking(Request $request, $id)
    {
        $request->validate(['reject_reason' => 'required|string|max:500']);
        $booking = Booking::with('payment')->findOrFail($id);

        if ($booking->status !== 'pending') {
            return back()->with('error', 'Booking ini tidak dalam status pending.');
        }

        try {
            DB::beginTransaction();

            $booking->update([
                'status' => 'cancelled',
                'notes'  => ($booking->notes ? $booking->notes . "\n\n" : '') . 'Admin Note (Rejected): ' . $request->reject_reason,
            ]);

            if ($booking->payment) {
                $booking->payment->update(['status' => 'cancelled']);
            }

            DB::commit();
            return redirect()->route('admin.bookings.show', $booking->id)->with('success', 'Booking berhasil ditolak.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal reject booking #' . $id . ': ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function updateBookingStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,assigned,in_progress,completed,cancelled,on_hold',
            'technician_id' => [
                Rule::requiredIf(fn() => $request->input('status') === 'assigned'),
                'nullable',
                'exists:users,id',
            ],
        ]);

        try {
            $booking = Booking::findOrFail($id);
            $booking->status = $validated['status'];
            if (!empty($validated['technician_id'])) {
                $booking->technician_id = $validated['technician_id'];
            }
            $booking->save();

            return redirect()->route('admin.bookings.show', $id)->with('success', 'Booking status updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating status: ' . $e->getMessage());
        }
    }

    /**
     * SERVICES
     */
    public function services()
    {
        $services = Service::orderBy('name')->paginate(10);
        return view('admin.services.index', compact('services'));
    }

    public function createService()
    {
        return view('admin.services.create');
    }

    public function storeService(Request $request)
    {
        $validated = $request->validate([
            'name'                     => 'required|string|max:255',
            'description'              => 'nullable|string',
            'price'                    => 'required|numeric|min:0',
            'estimated_duration_minutes' => 'nullable|integer|min:0',
        ]);

        Service::create($validated);
        return redirect()->route('admin.services.index')->with('success', 'Service created successfully.');
    }

    public function editService(Service $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    public function updateService(Request $request, Service $service)
    {
        $validated = $request->validate([
            'name'                     => 'required|string|max:255',
            'description'              => 'nullable|string',
            'price'                    => 'required|numeric|min:0',
            'estimated_duration_minutes' => 'nullable|integer|min:0',
        ]);

        $service->update($validated);
        return redirect()->route('admin.services.index')->with('success', 'Service updated successfully.');
    }

    public function deleteService(Service $service)
    {
        try {
            $service->delete();
            return redirect()->route('admin.services.index')->with('success', 'Service deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.services.index')->with('error', 'Error deleting service: ' . $e->getMessage());
        }
    }

    /**
     * PAYMENTS (Riwayat)
     */
    public function payments(Request $request)
    {
        $query = Payment::with(['booking.user'])->latest('created_at');

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $payments            = $query->paginate(10);
        $totalRevenue        = Payment::where('status', 'paid')->sum('amount');
        $pendingVerification = Payment::where('status', 'pending_verification')->count();
        $totalPayments       = Payment::count();
        $refundedAmount      = Payment::where('status', 'refunded')->sum('refund_amount');

        return view('admin.payments.index', compact(
            'payments',
            'totalRevenue',
            'pendingVerification',
            'totalPayments',
            'refundedAmount'
        ));
    }

    /**
     * PAYMENT VERIFICATION (antrian)
     */
    public function paymentVerification()
    {
        $payments = Payment::where('status', 'pending_verification')
            ->with('booking.user')
            ->orderBy('updated_at', 'asc')
            ->paginate(10);

        $pendingCount       = $payments->total();
        $approvedTodayCount = Payment::where('status', 'paid')
            ->whereDate('paid_at', Carbon::today())
            ->count();

        $technicians = User::isTechnician()->get();

        return view('admin.payments.verification', compact(
            'payments',
            'pendingCount',
            'approvedTodayCount',
            'technicians'
        ));
    }

    /**
     * VERIFY PAYMENT (approve / reject)
     * - COD (cash): bukti dari teknisi (payment_proof). Approve => payment:paid, booking:completed.
     * - Non-COD: approve => payment:paid, booking:assigned + butuh technician_id.
     */
    public function verifyPayment(Request $request, Payment $payment)
    {
        // Wajib teknisi hanya jika approve & BUKAN cash
        $validated = $request->validate([
            'verification_action' => 'required|in:approve,reject',
            'admin_notes'         => 'nullable|string|max:500',
            'technician_id'       => [
                Rule::requiredIf(function () use ($request, $payment) {
                    return $request->input('verification_action') === 'approve'
                        && ($payment->payment_method ?? null) !== 'cash';
                }),
                'nullable',
                'exists:users,id'
            ],
        ]);

        try {
            $action = $validated['verification_action'];
            $notes  = $validated['admin_notes'] ?? null;

            if ($action === 'approve') {
                DB::beginTransaction();

                // Set payment paid
                $payment->status              = 'paid';
                $payment->verification_status = 'approved';
                $payment->paid_at             = Carbon::now();
                if ($notes) {
                    $payment->admin_notes = $notes;
                }
                $payment->save();

                // Handle booking status berdasarkan metode bayar
                if ($payment->booking) {
                    if (($payment->payment_method ?? null) === 'cash') {
                        // COD: Langsung selesai
                        $payment->booking->status = 'completed';
                        // Bukti sudah diupload teknisi ke payment_proof, tidak perlu dipindah.
                    } else {
                        // Non-COD: Assigned ke teknisi yang dipilih
                        $techId = $validated['technician_id'] ?? null;
                        $payment->booking->status        = 'assigned';
                        $payment->booking->technician_id = $techId;
                    }
                    $payment->booking->save();
                }

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Pembayaran disetujui.'
                ]);
            }

            // REJECT
            DB::beginTransaction();

            // Simpan path lama untuk dihapus jika perlu
            $oldProof = $payment->payment_proof;

            $payment->status              = 'pending';
            $payment->verification_status = 'rejected';
            $payment->admin_notes         = $notes;
            // Bersihkan bukti agar user/teknisi bisa unggah ulang
            $payment->payment_proof       = null;
            $payment->save();

            if ($oldProof) {
                // hapus file lama (disk public)
                Storage::disk('public')->delete($oldProof);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran ditolak.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Gagal verifikasi payment #{$payment->id}: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error verifying payment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * REFUNDS
     */
    public function refundRequests(Request $request)
    {
        $query = Payment::where('status', 'pending_refund')
            ->where('refund_status', 'pending')
            ->with('booking.user');

        $payments = $query->orderBy('updated_at', 'asc')->paginate(10);

        $pendingRefundCount = (clone $query)->count();
        $approvedTodayCount = Payment::where('refund_status', 'approved')
            ->whereDate('refund_processed_at', Carbon::today())
            ->count();
        $totalRefunded = Payment::where('status', 'refunded')->sum('refund_amount');

        return view('admin.refunds.index', compact(
            'payments',
            'pendingRefundCount',
            'approvedTodayCount',
            'totalRefunded'
        ));
    }

    public function approveRefund(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'admin_notes' => 'nullable|string|max:500',
        ]);

        if ($payment->status !== 'pending_refund' || $payment->refund_status !== 'pending') {
            return response()->json(['success' => false, 'message' => 'Status pembayaran tidak valid.'], 422);
        }

        try {
            DB::beginTransaction();

            $payment->update([
                'status'              => 'refunded',
                'refund_status'       => 'approved',
                'refund_admin_notes'  => $validated['admin_notes'] ?? null,
                'refund_processed_at' => Carbon::now(),
            ]);

            if ($payment->booking) {
                $payment->booking->update(['status' => 'cancelled']);
            }

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Permintaan refund berhasil disetujui.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Gagal menyetujui refund #{$payment->id}: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal memproses: ' . $e->getMessage()], 500);
        }
    }

    public function rejectRefund(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'admin_notes' => 'required|string|min:5|max:500',
        ]);

        if ($payment->status !== 'pending_refund' || $payment->refund_status !== 'pending') {
            return response()->json(['success' => false, 'message' => 'Status pembayaran tidak valid.'], 422);
        }

        try {
            $payment->update([
                'status'              => 'paid',
                'refund_status'       => 'rejected',
                'refund_admin_notes'  => $validated['admin_notes'],
                'refund_processed_at' => Carbon::now(),
            ]);

            return response()->json(['success' => true, 'message' => 'Permintaan refund berhasil ditolak.']);
        } catch (\Exception $e) {
            Log::error("Gagal menolak refund #{$payment->id}: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal memproses: ' . $e->getMessage()], 500);
        }
    }

    /**
     * REPORTS
     */
    public function reports()
    {
        $now       = Carbon::now();
        $lastMonth = $now->clone()->subMonthNoOverflow();

        $currentMonthRevenue = Payment::where('status', 'paid')
            ->whereYear('paid_at', $now->year)
            ->whereMonth('paid_at', $now->month)
            ->sum('amount');

        $lastMonthRevenue = Payment::where('status', 'paid')
            ->whereYear('paid_at', $lastMonth->year)
            ->whereMonth('paid_at', $lastMonth->month)
            ->sum('amount');

        $currentMonthCompletedBookings = Booking::where('status', 'completed')
            ->whereYear('created_at', $now->year)
            ->whereMonth('created_at', $now->month)
            ->count();

        $lastMonthCompletedBookings = Booking::where('status', 'completed')
            ->whereYear('created_at', $lastMonth->year)
            ->whereMonth('created_at', $lastMonth->month)
            ->count();

        $currentMonthNewUsers = User::where('role', 'user')
            ->whereYear('created_at', $now->year)
            ->whereMonth('created_at', $now->month)
            ->count();

        $lastMonthNewUsers = User::where('role', 'user')
            ->whereYear('created_at', $lastMonth->year)
            ->whereMonth('created_at', $lastMonth->month)
            ->count();

        $currentMonthAvgRating = Rating::whereYear('created_at', $now->year)
            ->whereMonth('created_at', $now->month)
            ->avg('rating');

        $currentMonthSatisfaction = $currentMonthAvgRating ? $currentMonthAvgRating * 20 : 0;

        $lastMonthAvgRating = Rating::whereYear('created_at', $lastMonth->year)
            ->whereMonth('created_at', $lastMonth->month)
            ->avg('rating');

        $lastMonthSatisfaction = $lastMonthAvgRating ? $lastMonthAvgRating * 20 : 0;

        $summaryStats = [
            'monthlyRevenue'         => $currentMonthRevenue,
            'revenuePercentageChange' => $this->calculatePercentageChange($currentMonthRevenue, $lastMonthRevenue),
            'completedBookings'      => $currentMonthCompletedBookings,
            'bookingsPercentageChange' => $this->calculatePercentageChange($currentMonthCompletedBookings, $lastMonthCompletedBookings),
            'newCustomers'           => $currentMonthNewUsers,
            'usersPercentageChange'  => $this->calculatePercentageChange($currentMonthNewUsers, $lastMonthNewUsers),
            'satisfactionRate'       => $currentMonthSatisfaction,
            'satisfactionChange'     => $currentMonthSatisfaction - $lastMonthSatisfaction,
        ];

        // Revenue last 30 days
        $revenueDataRaw = Payment::where('status', 'paid')
            ->where('paid_at', '>=', $now->clone()->subDays(30))
            ->selectRaw('DATE(paid_at) as date, SUM(amount) as revenue')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $revenueLabels = $revenueDataRaw->map(fn($i) => (new Carbon($i->date))->format('d M'));
        $revenueValues = $revenueDataRaw->pluck('revenue');

        // Booking stats
        $bookingStats = Booking::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get();

        // Popular services (Top 3)
        $popularServices = Service::withCount('bookings')
            ->orderBy('bookings_count', 'desc')
            ->take(3)
            ->get();

        // Recent activities
        $recentActivities = Booking::with('user', 'services')
            ->whereIn('status', ['completed', 'assigned', 'pending_verification'])
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.reports.index', compact(
            'summaryStats',
            'revenueLabels',
            'revenueValues',
            'bookingStats',
            'popularServices',
            'recentActivities'
        ));
    }
}
