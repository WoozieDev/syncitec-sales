<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $password = env('SEED_DEFAULT_PASSWORD', 'password');

        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@local.test',
                'role' => 'superadmin',
                'is_active' => true,
            ],
            [
                'name' => 'Admin',
                'email' => 'admin@local.test',
                'role' => 'admin',
                'is_active' => true,
            ],
            [
                'name' => 'Manager',
                'email' => 'manager@local.test',
                'role' => 'manager',
                'is_active' => true,
            ],
            [
                'name' => 'Seller',
                'email' => 'seller@local.test',
                'role' => 'seller',
                'is_active' => true,
            ],
            [
                'name' => 'Customer Demo',
                'email' => 'customer@local.test',
                'role' => 'customer',
                'is_active' => true,
            ],
            [
                'name' => 'Support',
                'email' => 'support@local.test',
                'role' => 'support',
                'is_active' => true,
            ],
        ];

        foreach ($users as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make($password),
                    'is_active' => $data['is_active'],
                ]
            );

            // Asegura rol único (MVP)
            $user->syncRoles([$data['role']]);
        }
    }
}
