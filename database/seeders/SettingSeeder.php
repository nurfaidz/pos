<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::create([
            'company_name' => 'Masukkan nama kafe',
            'address' => 'Bantul, DI Yogyakarta',
            'phone' => '089182928',
            'note_type' => 1,
            'member_card_path' => 'img/member.png',
        ]);
    }
}
