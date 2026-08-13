<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Rank;
use App\Models\User;
use App\Services\MLMService;

class RankController extends Controller
{
    public function index()
    {
        $user = $this->authenticatedUser();

        if (! $user || $user->role?->slug !== 'user') {
            return redirect()->route('user.login');
        }

        [$powerLegBusiness, $weakerLegBusiness] = app(MLMService::class)->calculateLegBusiness($user);

        $allRanks = Rank::orderBy('rank_no', 'asc')->get();
        $currentRank = $user->rank;

        $nextRank = null;
        if ($currentRank) {
            $nextRank = Rank::where('rank_no', '>', $currentRank->rank_no)->orderBy('rank_no', 'asc')->first();
        } else {
            $nextRank = Rank::orderBy('rank_no', 'asc')->first();
        }

        return view('user.rank.index', compact('user', 'powerLegBusiness', 'weakerLegBusiness', 'allRanks', 'currentRank', 'nextRank'));
    }
}
