<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Income;
use App\Models\Transaction;
use Illuminate\Http\Request;

class GlobalSearchController extends Controller
{
    public function index(Request $request)
    {
        $query = trim((string) $request->get('q'));
        $user = $this->authenticatedUser();

        if (! $user || mb_strlen($query) < 2) {
            return response()->json(['results' => []]);
        }

        $results = Transaction::where('user_id', $user->id)
            ->where(fn ($transaction) => $transaction->where('transaction_no', 'like', "%{$query}%")
                ->orWhere('remark', 'like', "%{$query}%"))
            ->latest()
            ->limit(5)
            ->get()
            ->map(fn ($transaction) => [
                'title' => $transaction->transaction_no . ' · ₹ ' . number_format($transaction->amount, 2),
                'meta' => ucfirst($transaction->type) . ' · ' . $transaction->remark,
                'url' => route('user.transaction.index', ['search' => $transaction->transaction_no]),
                'icon' => 'mdi-receipt',
            ])->values();

        if ($results->count() < 8) {
            $incomes = Income::where('user_id', $user->id)
                ->where(fn ($income) => $income->where('income_type', 'like', "%{$query}%")
                    ->orWhere('amount', 'like', "%{$query}%"))
                ->latest()
                ->limit(8 - $results->count())
                ->get()
                ->map(fn ($income) => [
                    'title' => ucfirst(str_replace('_', ' ', $income->income_type)) . ' · ₹ ' . number_format($income->amount, 2),
                    'meta' => 'Level ' . $income->level . ' · ' . $income->created_at->format('d M Y'),
                    'url' => route('user.income.index', ['type' => $income->income_type]),
                    'icon' => 'mdi-cash-multiple',
                ]);

            $results = $results->concat($incomes)->values();
        }

        return response()->json(['results' => $results]);
    }
}
