<?php

namespace Database\Seeders;

use App\Models\Rank;
use Illuminate\Database\Seeder;

class RankSeeder extends Seeder
{
    public function run(): void
    {
        $ranks = [
            [
                'rank_no' => 1,
                'name' => 'Manager',
                'power_leg_target' => 1620000,
                'weaker_leg_target' => 648000, // 40% of power leg target
                'monthly_bonus' => 16200,
            ],
            [
                'rank_no' => 2,
                'name' => 'Sr Manager',
                'power_leg_target' => 4860000,
                'weaker_leg_target' => 1944000,
                'monthly_bonus' => 48600,
            ],
            [
                'rank_no' => 3,
                'name' => 'Director',
                'power_leg_target' => 12960000,
                'weaker_leg_target' => 5184000,
                'monthly_bonus' => 129600,
            ],
            [
                'rank_no' => 4,
                'name' => 'Executive Director',
                'power_leg_target' => 34020000,
                'weaker_leg_target' => 13608000,
                'monthly_bonus' => 340200,
            ],
            [
                'rank_no' => 5,
                'name' => 'Sapphire',
                'power_leg_target' => 68040000,
                'weaker_leg_target' => 27216000,
                'monthly_bonus' => 680400,
            ],
            [
                'rank_no' => 6,
                'name' => 'Diamond',
                'power_leg_target' => 120000000,
                'weaker_leg_target' => 48000000,
                'monthly_bonus' => 1200000,
            ],
        ];

        foreach ($ranks as $rank) {
            Rank::updateOrCreate(['rank_no' => $rank['rank_no']], $rank);
        }
    }
}