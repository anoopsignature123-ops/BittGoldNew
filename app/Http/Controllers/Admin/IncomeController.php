<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Income;
use Illuminate\Http\Request;

class IncomeController extends Controller
{
    public function index(Request $request)
    {
        $query = Income::with(['user', 'fromUser']);

        // Filter by income type
        if ($request->filled('type')) {
            $query->where('income_type', $request->type);
        }

        // Search by receiver user name or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('referral_code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $reportTotal = (clone $query)->sum('amount');
        $reportCount = (clone $query)->count();
        $incomes = $query->latest()->paginate(10)->withQueryString();

        return view('admin.incomes.index', compact('incomes', 'reportTotal', 'reportCount'));
    }
}
