<?php

namespace Database\Seeders;

use App\Models\Member;
use Illuminate\Database\Seeder;

class MemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $members = [
            [
                'member_code' => 'M0001',
                'member_name' => 'Dummy Member 1',
                'status' => 1
            ],
            [
                'member_code' => 'M0002',
                'member_name' => 'Dummy Member 2',
                'phone' => '08123456789',
                'email' => 'member@gmail.com',
                'discount_member' => 0.02,
                'status' => 2
            ]
        ];

        foreach ($members as $member) {
            Member::create($member);
        }
    }
}
