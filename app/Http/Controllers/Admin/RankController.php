<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class RankController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('rank')->where('role_id', '!=', 1);  

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('referral_code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('rank_id')) {
            $query->where('rank_id', $request->rank_id);
        }

        $users = $query->latest()->paginate(10)->withQueryString();
        $ranks = \App\Models\Rank::all();

        return view('admin.ranks.index', compact('users', 'ranks'));
    }
}