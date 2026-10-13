<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RocPayout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RocPayoutController extends Controller
{
    public function index(Request $request)
    {
        // Auto-sync any RocPayout statuses that differ from their linked Withdrawal status
        try {
            DB::statement("UPDATE roc_payouts rp INNER JOIN withdrawals w ON rp.withdrawal_id = w.id SET rp.status = w.status WHERE rp.status != w.status");
        } catch (\Exception $e) {
            // Ignore DB errors if any SQL dialect issue occurs
        }

        $query = RocPayout::with(['user.sponsor', 'user.kyc', 'investment', 'withdrawal']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($u) use ($search) {
                    $u->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('referral_code', 'like', "%{$search}%");
                })->orWhereHas('user.sponsor', function ($s) use ($search) {
                    $s->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('referral_code', 'like', "%{$search}%");
                });
            });
        }

        if ($request->filled('from_date')) {
            $query->whereDate('payout_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('payout_date', '<=', $request->to_date);
        }

        $reportTotal = (clone $query)->sum('amount');
        $reportCount = (clone $query)->count();
        
        // Overview Summary Statistics
        $totalRocAmount = RocPayout::sum('amount');
        $pendingRocAmount = RocPayout::where('status', 'pending')->sum('amount');
        $pendingRocCount = RocPayout::where('status', 'pending')->count();
        $processedRocAmount = RocPayout::whereIn('status', ['approved', 'processed'])->sum('amount');
        $processedRocCount = RocPayout::whereIn('status', ['approved', 'processed'])->count();
        $activePackagesVolume = \App\Models\Investment::where('status', 'active')->sum('amount');

        $payouts = $query->latest('payout_date')->paginate(15)->withQueryString();

        return view('admin.roc.index', compact(
            'payouts',
            'reportTotal',
            'reportCount',
            'totalRocAmount',
            'pendingRocAmount',
            'pendingRocCount',
            'processedRocAmount',
            'processedRocCount',
            'activePackagesVolume'
        ));
    }
}
