<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\User;
use Illuminate\Http\Request;

class DepositController extends Controller
{
     
    public function index()
    {
        $user = $this->authenticatedUser();

        if (! $user || $user->role?->slug !== 'user') {
            return redirect()->route('user.login')->with('error', 'Please login first.');
        }

        $deposits = Deposit::where('user_id', $user->id)->latest()->paginate(10);

        return view('user.deposit.index', compact('user', 'deposits'));
    }

    public function store(Request $request)
    {
        $user = $this->authenticatedUser();

        if (! $user || $user->role?->slug !== 'user') {
            return redirect()->route('user.login');
        }

        $request->validate([
            'amount' => 'required|numeric|min:1',
            'reference_no' => 'required|string|max:255',
        ]);

        Deposit::create([
            'user_id' => $user->id,
            'amount' => $request->amount,
            'reference_no' => $request->reference_no,
            'status' => 'pending',
        ]);

        return redirect()->route('user.deposit.index')->with('success', 'Deposit request of ₹' . number_format($request->amount, 2) . ' submitted successfully. Waiting for admin approval.');
    }

}