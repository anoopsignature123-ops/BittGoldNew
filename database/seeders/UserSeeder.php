<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\UserReferral;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userRole = Role::where('slug', 'user')->first();
        $adminRole = Role::where('slug', 'admin')->first();

        // 1. Root User (Mahadev) — No Sponsor
        $rootUser = User::updateOrCreate(
            ['referral_code' => 'BG906518'],
            [
                'role_id' => $userRole?->id,
                'name' => 'Mahadev',
                'email' => 'pkmishra00014@gmail.com',
                'mobile' => '9876543210',
                'sponsor_id' => null,
                'referral_code' => 'BG906518',
                'status' => 'inactive',
                'password' => Hash::make('Password@123'),
                'plain_password' => 'Password@123',
                'email_verified_at' => now(),
            ]
        );

        // 2. Create Total 24 Downline Users in a Chain (Level by Level)
        $previousUser = $rootUser;

        for ($i = 1; $i <= 24; $i++) {
            $referralCode = 'BG' . (906518 + $i);
            $mobileNumber = '98765432' . str_pad($i, 2, '0', STR_PAD_LEFT);

            // Conditional Name Assignment (1 to 21 Mahadev, then 22, 23, 24 specific names)
            if ($i <= 21) {
                $userName = 'Mahadev';
            } elseif ($i === 22) {
                $userName = 'Board Of Director';
            } elseif ($i === 23) {
                $userName = 'Executive Body';
            } else {
                $userName = 'Board Member';
            }

            $downlineUser = User::updateOrCreate(
                ['referral_code' => $referralCode],
                [
                    'role_id' => $userRole?->id,
                    'name' => $userName,
                    'email' => 'pkmishra00014@gmail.com',
                    'mobile' => $mobileNumber,
                    'sponsor_id' => $previousUser->id,
                    'referral_code' => $referralCode,
                    'status' => 'inactive',
                    'password' => Hash::make('Password@123'),
                    'plain_password' => 'Password@123',
                    'email_verified_at' => now(),
                ]
            );

            UserReferral::updateOrCreate(
                ['user_id' => $downlineUser->id],
                [
                    'sponsor_id' => $previousUser->id,
                    'sponsor_referral_code' => $previousUser->referral_code,
                ]
            );

            // Chain ko aage badhane ke liye current user agle user ka sponsor ban jayega
            $previousUser = $downlineUser;
        }
    }
}