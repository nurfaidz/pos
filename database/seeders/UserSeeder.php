<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $password = 'password';
        $user = [
            [
                'name' => 'admin',
                'email' => 'admin@example.com',
                'password' => Hash::make($password),
                'role' => 1,
            ],
            [
                'name' => 'kasir',
                'email' => 'kasir@example.com',
                'password' => Hash::make($password),
                'role' => 2,
            ],
        ];

        foreach ($user as $u) {
            User::create($u);
        }
    }
}
