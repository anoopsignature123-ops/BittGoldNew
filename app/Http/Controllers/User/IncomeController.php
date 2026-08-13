<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Income;
use Illuminate\Http\Request;

class IncomeController extends Controller
{
    public function index(Request $request)
    {
        $user = $this->authenticatedUser();

        if (!$user || $user->role?->slug !== 'user') {
            return redirect()->route('user.login');
        }

        $query = Income::where('user_id', $user->id)->with('fromUser');

        // Filter by income type if provided
        if ($request->filled('type')) {
            $query->where('income_type', $request->type);
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

        return view('user.income.index', compact('user', 'incomes', 'reportTotal', 'reportCount'));
    }
}
