<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index(Request $request)
    {
        $user = $this->authenticatedUser();

        if (!$user || $user->role?->slug !== 'user') {
            return redirect()->route('user.login');
        }

        $query = User::where('sponsor_id', $user->id)->with('investments', 'rank');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('referral_code', 'like', "%{$search}%");
            });
        }

        $teamMembers = $query->latest()->paginate(10)->withQueryString();

        // Total team stats
        $totalDirects = User::where('sponsor_id', $user->id)->count();
        $activeDirects = User::where('sponsor_id', $user->id)->where('status', 'active')->count();

        return view('user.team.index', compact('user', 'teamMembers', 'totalDirects', 'activeDirects'));
    }

    public function tree(User $user = null)
    {
        $currentUser = $this->authenticatedUser();
        if (!$currentUser || $currentUser->role?->slug !== 'user') {
            return redirect()->route('user.login');
        }

        // Agar user ne kisi downline ka node khola hai, toh root user woh ban jayega
        $rootUser = ($user && $user->exists) ? $user : $currentUser;

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'user' => $rootUser,
                'tree' => $this->formatTreeNode($rootUser)
            ]);
        }

        $treeData = $this->formatTreeNode($rootUser);

        return view('user.team.tree', compact('rootUser', 'treeData'));
    }

    private function formatTreeNode($user)
    {
        if (!$user) return null;

        $activeInv = $user->investments()->where('status', 'active')->sum('amount');
        $sponsor = User::where('id', $user->sponsor_id)->first();

        $children = [];
        foreach ($user->referrals as $child) {
            $children[] = $this->formatTreeNode($child);
        }

        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'mobile' => $user->mobile ?? 'N/A',
            'unique_id' => $user->referral_code ?? $user->unique_id,
            'sponsor_id' => $sponsor ? ($sponsor->referral_code ?? $sponsor->unique_id) : 'System',
            'sponsor_name' => $sponsor ? $sponsor->name : 'System',
            'status' => $user->status,
            'active_package' => $activeInv > 0 ? '' . number_format($activeInv, 2) : 'No Package',
            'active_investment' => $activeInv,
            'children' => $children
        ];
    }
}