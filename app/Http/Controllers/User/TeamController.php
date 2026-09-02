<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\MLMService;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index(Request $request)
    {
        $user = $this->authenticatedUser();

        if (!$user || $user->role?->slug !== 'user') {
            return redirect()->route('user.login');
        }

        $mlmService = new MLMService();
        $teamStats = $mlmService->getTeamStats($user->id);
        $downlineLevels = $teamStats['downline_levels'];
        $availableLevels = $teamStats['available_levels'];

        // Determine which user IDs to include based on level filter
        if ($request->filled('level')) {
            $targetLevel = (int) $request->level;
            $targetUserIds = [];
            foreach ($downlineLevels as $memberId => $lvl) {
                if ($lvl === $targetLevel) {
                    $targetUserIds[] = $memberId;
                }
            }
        } else {
            $targetUserIds = array_keys($downlineLevels);
        }

        if (empty($targetUserIds)) {
            $query = User::whereRaw('1 = 0');
        } else {
            $query = User::whereIn('id', $targetUserIds)->with(['investments', 'rank', 'sponsor']);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('referral_code', 'like', "%{$search}%")
                    ->orWhere('mobile', 'like', "%{$search}%");
            });
        }

        $teamMembers = $query->latest()->paginate(10)->withQueryString();

        // Attach the level attribute to each member model for the view
        $teamMembers->getCollection()->transform(function ($member) use ($downlineLevels) {
            $member->level = $downlineLevels[$member->id] ?? 1;
            return $member;
        });

        return view('user.team.index', [
            'user' => $user,
            'teamMembers' => $teamMembers,
            'teamStats' => $teamStats,
            'availableLevels' => $availableLevels,
            'totalDirects' => $teamStats['direct_total'],
            'activeDirects' => $teamStats['direct_active'],
            'inactiveDirects' => $teamStats['direct_inactive'],
            'totalTeam' => $teamStats['total_team'],
            'activeTeam' => $teamStats['total_active'],
            'inactiveTeam' => $teamStats['total_inactive'],
        ]);
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