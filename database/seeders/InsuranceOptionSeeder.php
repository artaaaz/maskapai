<?php

namespace Database\Seeders;

use App\Models\InsuranceOption;
use Illuminate\Database\Seeder;

class InsuranceOptionSeeder extends Seeder
{
    public function run(): void
    {
        InsuranceOption::firstOrCreate(
            ['name' => 'Asuransi Perjalanan'],
            [
                'description' => 'Perlindungan dasar untuk perjalanan Anda, termasuk kecelakaan pribadi dan keterlambatan penerbangan.',
                'coverage_amount' => 50000000,
                'price' => 50000,
                'type' => 'disruption',
                'is_active' => true,
            ]
        );

        InsuranceOption::firstOrCreate(
            ['name' => 'Asuransi Premium'],
            [
                'description' => 'Perlindungan lengkap dengan coverage tinggi, termasuk asuransi bagasi, kesehatan, dan pembatalan perjalanan.',
                'coverage_amount' => 100000000,
                'price' => 100000,
                'type' => 'bundle',
                'is_active' => true,
            ]
        );
    }
}