<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\UserReferral;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([RoleSeeder::class, RankSeeder::class]);

        $adminRole = Role::where('slug', 'admin')->first();
        $userRole = Role::where('slug', 'user')->first();

        $admin = User::updateOrCreate(
            ['email' => 'admin@BittGold.com'],
            [
                'role_id' => $adminRole?->id,
                'name' => 'Admin User',
                'email' => 'admin@BittGold.com',
                'mobile' => '1234567890',
                'referral_code' => 'BG906508',
                'status' => 'active',
                'password' => Hash::make('Admin@123'),
                'plain_password' => 'Admin@123',
                'email_verified_at' => now(),
                 
            ]
        );

        $defaultUser = User::updateOrCreate(
            ['email' => 'root@BittGold.com'],
            [
                'role_id' => $userRole?->id,
                'name' => 'Root User',
                'email' => 'root@BittGold.com',
                'mobile' => '1234567890',
                'sponsor_id' => null,
                'referral_code' => 'BG906518',
                'status' => 'inactive',
                'password' => Hash::make('Root@123'),
                'plain_password' => 'Root@123',
                'email_verified_at' => now(),
            ]
        );

        // UserReferral::updateOrCreate(
        //     ['user_id' => $defaultUser->id],
        //     [
        //         'sponsor_id' => $admin->id,
        //         'sponsor_referral_code' => $admin->referral_code,   
        //     ]
        // );
    }
}