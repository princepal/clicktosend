<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LoadboardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $loadboards = [
            [
                'name' => 'DAT One',
                'url' => 'https://one.dat.com/search-loads-ow',
                'logo' => 'dat-one.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Loadlink',
                'url' => 'https://web.loadlink.ca/',
                'logo' => 'loadlink-new.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('loadboards')->insert($loadboards);
    }
}
