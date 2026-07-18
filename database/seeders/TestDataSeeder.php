<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Promo;
use App\Models\InsuranceOption;
use Carbon\Carbon;

class TestDataSeeder extends Seeder
{
    public function run(): void
    {
        // Create Promos (pake firstOrCreate biar gak duplikat)
        Promo::firstOrCreate(
            ['code' => 'PROMO2024'],
            [
                'name' => 'Promo Tahun Baru 2024',
                'description' => 'Diskon 10% untuk semua penerbangan',
                'discount_type' => 'percentage',
                'discount_value' => 10,
                'min_transaction' => 500000,
                'max_discount' => 100000,
                'valid_from' => Carbon::now()->subDays(7),
                'valid_until' => Carbon::now()->addDays(30),
                'usage_limit' => 100,
                'used_count' => 0,
                'is_active' => true,
            ]
        );

        Promo::firstOrCreate(
            ['code' => 'HEMAT50K'],
            [
                'name' => 'Hemat 50K',
                'description' => 'Potongan langsung 50.000',
                'discount_type' => 'fixed',
                'discount_value' => 50000,
                'min_transaction' => 300000,
                'max_discount' => 0,
                'valid_from' => Carbon::now()->subDays(3),
                'valid_until' => Carbon::now()->addDays(14),
                'usage_limit' => 50,
                'used_count' => 5,
                'is_active' => true,
            ]
        );

        Promo::firstOrCreate(
            ['code' => 'FLIGHTTBD'],
            [
                'name' => 'Flight TBD Special',
                'description' => 'Diskon khusus penerbangan TBD',
                'discount_type' => 'percentage',
                'discount_value' => 15,
                'min_transaction' => 400000,
                'max_discount' => 150000,
                'valid_from' => Carbon::now()->subDays(5),
                'valid_until' => Carbon::now()->addDays(20),
                'usage_limit' => 200,
                'used_count' => 10,
                'is_active' => true,
            ]
        );

        // Create Insurance Options
        InsuranceOption::firstOrCreate(
            ['name' => 'Asuransi Bagasi'],
            [
                'description' => 'Lindungi bagasi dari kerusakan atau kehilangan hingga IDR 25.000.000',
                'coverage_amount' => 25000000,
                'price' => 31920,
                'type' => 'baggage',
                'is_active' => true,
            ]
        );

        InsuranceOption::firstOrCreate(
            ['name' => 'Proteksi Keterlambatan Plus'],
            [
                'description' => 'Menjamin keterlambatan penerbangan di atas 90 menit hingga IDR 600.000',
                'coverage_amount' => 600000,
                'price' => 99700,
                'type' => 'delay',
                'is_active' => true,
            ]
        );

        InsuranceOption::firstOrCreate(
            ['name' => 'Proteksi Keterlambatan'],
            [
                'description' => 'Kompensasi IDR 200.000 per 2 jam untuk segala resiko keterlambatan',
                'coverage_amount' => 600000,
                'price' => 39000,
                'type' => 'delay',
                'is_active' => true,
            ]
        );

        InsuranceOption::firstOrCreate(
            ['name' => 'Proteksi Gangguan Penerbangan'],
            [
                'description' => 'Dapatkan kompensasi jika penerbangan dibatalkan atau delay lebih dari 2 jam',
                'coverage_amount' => 1962272,
                'price' => 214755,
                'type' => 'disruption',
                'is_active' => true,
            ]
        );

        InsuranceOption::firstOrCreate(
            ['name' => 'Bundle Perlindungan Penuh'],
            [
                'description' => 'Manfaat asuransi Perlindungan Penuh s.d. IDR 500.000.000 + Proteksi Reschedule Maskapai IDR 300.000',
                'coverage_amount' => 500000000,
                'price' => 148000,
                'type' => 'bundle',
                'is_active' => true,
            ]
        );

        $this->command->info('✅ Test data created/updated successfully!');
        $this->command->info('📊 Promos: ' . Promo::count());
        $this->command->info('🛡️ Insurance Options: ' . InsuranceOption::count());
    }
}