<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

class GlobalSearchController extends Controller
{
    public function index(Request $request)
    {
        $query = trim((string) $request->get('q'));

        if (mb_strlen($query) < 2) {
            return response()->json(['results' => []]);
        }

        $results = User::whereHas('role', fn ($role) => $role->where('slug', 'user'))
            ->where(fn ($user) => $user->where('name', 'like', "%{$query}%")
                ->orWhere('email', 'like', "%{$query}%")
                ->orWhere('mobile', 'like', "%{$query}%")
                ->orWhere('referral_code', 'like', "%{$query}%"))
            ->limit(5)
            ->get()
            ->map(fn ($user) => [
                'title' => $user->name,
                'meta' => $user->email . ' · ' . $user->referral_code,
                'url' => route('admin.users.view', $user),
                'icon' => 'mdi-account-outline',
            ])->values();

        if ($results->count() < 8) {
            $transactions = Transaction::with('user')
                ->where(fn ($transaction) => $transaction->where('transaction_no', 'like', "%{$query}%")
                    ->orWhere('remark', 'like', "%{$query}%")
                    ->orWhereHas('user', fn ($user) => $user->where('name', 'like', "%{$query}%")
                        ->orWhere('email', 'like', "%{$query}%")))
                ->latest()
                ->limit(8 - $results->count())
                ->get()
                ->map(fn ($transaction) => [
                    'title' => $transaction->transaction_no . ' · ₹ ' . number_format($transaction->amount, 2),
                    'meta' => ($transaction->user?->name ?? 'Unknown member') . ' · ' . $transaction->remark,
                    'url' => route('admin.transactions.index', ['search' => $transaction->transaction_no]),
                    'icon' => 'mdi-receipt',
                ]);

            $results = $results->concat($transactions)->values();
        }

        return response()->json(['results' => $results]);
    }
}
