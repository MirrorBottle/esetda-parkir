<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BiroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('biros')->insert([
            [
                'code' => 'HUKUM',
                'name' => 'Biro Hukum',
            ],
            [
                'code' => 'UMUM',
                'name' => 'Biro Umum',
            ],
            [
                'code' => 'ADPIM',
                'name' => 'Biro ADPIM',
            ],
            [
                'code' => 'ADBANG',
                'name' => 'Biro ADBANG',
            ],
            [
                'code' => 'KESRA',
                'name' => 'Biro Kesra',
            ],
            [
                'code' => 'EKONOMI',
                'name' => 'Biro Ekonomi',
            ],
            [
                'code' => 'ORGANISASI',
                'name' => 'Biro Organisasi',
            ],
            [
                'code' => 'BARJAS',
                'name' => 'Biro BARJAS',
            ],
        ]);
    }
}
