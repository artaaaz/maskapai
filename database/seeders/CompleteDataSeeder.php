<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Airline;
use App\Models\Airport;
use App\Models\Airplane;
use App\Models\Flight;
use Carbon\Carbon;

class CompleteDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🛫 Memulai seeding data lengkap...');

        // 1. AIRLINES (Maskapai)
        $this->command->info('✈️ Membuat data maskapai...');
        
        $airlines = [
            [
                'name' => 'Garuda Indonesia',
                'code' => 'GA',
                'description' => 'Maskapai nasional Indonesia dengan layanan premium',
                'logo' => null,
                'photos' => null,
            ],
            [
                'name' => 'Lion Air',
                'code' => 'JT',
                'description' => 'Maskapai low-cost terbesar di Indonesia',
                'logo' => null,
                'photos' => null,
            ],
            [
                'name' => 'Citilink',
                'code' => 'QG',
                'description' => 'Maskapai low-cost dari Garuda Indonesia Group',
                'logo' => null,
                'photos' => null,
            ],
            [
                'name' => 'Batik Air',
                'code' => 'ID',
                'description' => 'Maskapai full-service dari Lion Group',
                'logo' => null,
                'photos' => null,
            ],
            [
                'name' => 'AirAsia Indonesia',
                'code' => 'QZ',
                'description' => 'Maskapai low-cost regional',
                'logo' => null,
                'photos' => null,
            ],
        ];

        foreach ($airlines as $airline) {
            Airline::firstOrCreate(['code' => $airline['code']], $airline);
        }

        $this->command->info('✅ ' . Airline::count() . ' maskapai dibuat');

        // 2. AIRPORTS (Bandara)
        $this->command->info('🏢 Membuat data bandara...');
        
        $airports = [
            [
                'name' => 'Soekarno-Hatta International Airport',
                'city' => 'Jakarta',
                'country' => 'Indonesia',
                'iata_code' => 'CGK',
                'terminal' => 'Terminal 1, 2, 3',
                'timezone' => 'Asia/Jakarta',
            ],
            [
                'name' => 'Juanda International Airport',
                'city' => 'Surabaya',
                'country' => 'Indonesia',
                'iata_code' => 'SUB',
                'terminal' => 'Terminal 1, 2',
                'timezone' => 'Asia/Jakarta',
            ],
            [
                'name' => 'Ngurah Rai International Airport',
                'city' => 'Denpasar',
                'country' => 'Indonesia',
                'iata_code' => 'DPS',
                'terminal' => 'Terminal I (Domestik), Terminal II (Internasional)',
                'timezone' => 'Asia/Makassar',
            ],
            [
                'name' => 'Kualanamu International Airport',
                'city' => 'Medan',
                'country' => 'Indonesia',
                'iata_code' => 'KNO',
                'terminal' => 'Terminal 1',
                'timezone' => 'Asia/Jakarta',
            ],
            [
                'name' => 'Sultan Hasanuddin International Airport',
                'city' => 'Makassar',
                'country' => 'Indonesia',
                'iata_code' => 'UPG',
                'terminal' => 'Terminal 1',
                'timezone' => 'Asia/Makassar',
            ],
            [
                'name' => 'Adisutjipto International Airport',
                'city' => 'Yogyakarta',
                'country' => 'Indonesia',
                'iata_code' => 'JOG',
                'terminal' => 'Terminal 1',
                'timezone' => 'Asia/Jakarta',
            ],
        ];

        foreach ($airports as $airport) {
            Airport::firstOrCreate(['iata_code' => $airport['iata_code']], $airport);
        }

        $this->command->info('✅ ' . Airport::count() . ' bandara dibuat');

        // 3. AIRPLANES (Pesawat)
        $this->command->info('✈️ Membuat data pesawat...');
        
        $garuda = Airline::where('code', 'GA')->first();
        $lion = Airline::where('code', 'JT')->first();
        $citilink = Airline::where('code', 'QG')->first();
        $batik = Airline::where('code', 'ID')->first();

        $airplanes = [
            [
                'airline_id' => $garuda->id,
                'model' => 'Boeing 737-800',
                'registration_number' => 'PK-GFA',
                'capacity' => 180,
                'description' => 'Pesawat narrow-body untuk rute domestik',
                'photos' => null,
            ],
            [
                'airline_id' => $garuda->id,
                'model' => 'Airbus A330-300',
                'registration_number' => 'PK-GPI',
                'capacity' => 293,
                'description' => 'Pesawat wide-body untuk rute internasional',
                'photos' => null,
            ],
            [
                'airline_id' => $lion->id,
                'model' => 'Boeing 737-900ER',
                'registration_number' => 'PK-LAX',
                'capacity' => 215,
                'description' => 'Pesawat kapasitas besar untuk rute padat',
                'photos' => null,
            ],
            [
                'airline_id' => $lion->id,
                'model' => 'Airbus A320-200',
                'registration_number' => 'PK-LAQ',
                'capacity' => 180,
                'description' => 'Pesawat narrow-body standar',
                'photos' => null,
            ],
            [
                'airline_id' => $citilink->id,
                'model' => 'Airbus A320-200',
                'registration_number' => 'PK-GQC',
                'capacity' => 180,
                'description' => 'Pesawat untuk rute domestik Citilink',
                'photos' => null,
            ],
            [
                'airline_id' => $batik->id,
                'model' => 'Boeing 737-800',
                'registration_number' => 'PK-LBS',
                'capacity' => 162,
                'description' => 'Pesawat full-service Batik Air',
                'photos' => null,
            ],
        ];

        foreach ($airplanes as $airplane) {
            Airplane::firstOrCreate(
                ['registration_number' => $airplane['registration_number']],
                $airplane
            );
        }

        $this->command->info('✅ ' . Airplane::count() . ' pesawat dibuat');

        // 4. FLIGHTS (Jadwal Penerbangan)
        $this->command->info('📅 Membuat jadwal penerbangan...');
        
        $cgk = Airport::where('iata_code', 'CGK')->first();
        $sub = Airport::where('iata_code', 'SUB')->first();
        $dps = Airport::where('iata_code', 'DPS')->first();
        $kno = Airport::where('iata_code', 'KNO')->first();
        $upg = Airport::where('iata_code', 'UPG')->first();
        $jog = Airport::where('iata_code', 'JOG')->first();

        $pkGfa = Airplane::where('registration_number', 'PK-GFA')->first();
        $pkLax = Airplane::where('registration_number', 'PK-LAX')->first();
        $pkGqc = Airplane::where('registration_number', 'PK-GQC')->first();
        $pkLbs = Airplane::where('registration_number', 'PK-LBS')->first();

        // Buat penerbangan untuk 7 hari ke depan
        for ($day = 0; $day < 7; $day++) {
            $date = Carbon::now()->addDays($day);

            // CGK → SUB (Jakarta - Surabaya)
            Flight::firstOrCreate([
                'airline_id' => $garuda->id,
                'flight_number' => 'GA123',
                'departure_airport_id' => $cgk->id,
                'arrival_airport_id' => $sub->id,
                'departure_time' => $date->copy()->setTime(5, 0),
            ], [
                'airplane_id' => $pkGfa->id,
                'arrival_time' => $date->copy()->setTime(6, 30),
                'price' => 850000,
                'available_seats' => 120,
                'duration_minutes' => 90,
                'baggage_allowance_kg' => 20,
                'refund_policy' => 'Bisa refund dengan potongan 10% sebelum keberangkatan',
            ]);

            Flight::firstOrCreate([
                'airline_id' => $lion->id,
                'flight_number' => 'JT456',
                'departure_airport_id' => $cgk->id,
                'arrival_airport_id' => $sub->id,
                'departure_time' => $date->copy()->setTime(8, 0),
            ], [
                'airplane_id' => $pkLax->id,
                'arrival_time' => $date->copy()->setTime(9, 30),
                'price' => 650000,
                'available_seats' => 150,
                'duration_minutes' => 90,
                'baggage_allowance_kg' => 15,
                'refund_policy' => 'Tidak bisa refund, hanya bisa reschedule',
            ]);

            Flight::firstOrCreate([
                'airline_id' => $citilink->id,
                'flight_number' => 'QG789',
                'departure_airport_id' => $cgk->id,
                'arrival_airport_id' => $sub->id,
                'departure_time' => $date->copy()->setTime(14, 0),
            ], [
                'airplane_id' => $pkGqc->id,
                'arrival_time' => $date->copy()->setTime(15, 30),
                'price' => 550000,
                'available_seats' => 100,
                'duration_minutes' => 90,
                'baggage_allowance_kg' => 15,
                'refund_policy' => 'Bisa refund dengan potongan 20% sebelum keberangkatan',
            ]);

            // CGK → DPS (Jakarta - Bali)
            Flight::firstOrCreate([
                'airline_id' => $garuda->id,
                'flight_number' => 'GA201',
                'departure_airport_id' => $cgk->id,
                'arrival_airport_id' => $dps->id,
                'departure_time' => $date->copy()->setTime(6, 0),
            ], [
                'airplane_id' => $pkGfa->id,
                'arrival_time' => $date->copy()->setTime(9, 0),
                'price' => 1200000,
                'available_seats' => 140,
                'duration_minutes' => 180,
                'baggage_allowance_kg' => 20,
                'refund_policy' => 'Bisa refund dengan potongan 10% sebelum keberangkatan',
            ]);

            Flight::firstOrCreate([
                'airline_id' => $batik->id,
                'flight_number' => 'ID6789',
                'departure_airport_id' => $cgk->id,
                'arrival_airport_id' => $dps->id,
                'departure_time' => $date->copy()->setTime(10, 0),
            ], [
                'airplane_id' => $pkLbs->id,
                'arrival_time' => $date->copy()->setTime(13, 0),
                'price' => 950000,
                'available_seats' => 130,
                'duration_minutes' => 180,
                'baggage_allowance_kg' => 20,
                'refund_policy' => 'Bisa refund dengan potongan 15% sebelum keberangkatan',
            ]);

            // CGK → KNO (Jakarta - Medan)
            Flight::firstOrCreate([
                'airline_id' => $lion->id,
                'flight_number' => 'JT321',
                'departure_airport_id' => $cgk->id,
                'arrival_airport_id' => $kno->id,
                'departure_time' => $date->copy()->setTime(7, 0),
            ], [
                'airplane_id' => $pkLax->id,
                'arrival_time' => $date->copy()->setTime(9, 30),
                'price' => 900000,
                'available_seats' => 160,
                'duration_minutes' => 150,
                'baggage_allowance_kg' => 15,
                'refund_policy' => 'Tidak bisa refund, hanya bisa reschedule',
            ]);

            // SUB → DPS (Surabaya - Bali)
            Flight::firstOrCreate([
                'airline_id' => $citilink->id,
                'flight_number' => 'QG555',
                'departure_airport_id' => $sub->id,
                'arrival_airport_id' => $dps->id,
                'departure_time' => $date->copy()->setTime(9, 0),
            ], [
                'airplane_id' => $pkGqc->id,
                'arrival_time' => $date->copy()->setTime(10, 30),
                'price' => 600000,
                'available_seats' => 110,
                'duration_minutes' => 90,
                'baggage_allowance_kg' => 15,
                'refund_policy' => 'Bisa refund dengan potongan 20% sebelum keberangkatan',
            ]);

            // CGK → UPG (Jakarta - Makassar)
            Flight::firstOrCreate([
                'airline_id' => $garuda->id,
                'flight_number' => 'GA401',
                'departure_airport_id' => $cgk->id,
                'arrival_airport_id' => $upg->id,
                'departure_time' => $date->copy()->setTime(11, 0),
            ], [
                'airplane_id' => $pkGfa->id,
                'arrival_time' => $date->copy()->setTime(14, 0),
                'price' => 1500000,
                'available_seats' => 125,
                'duration_minutes' => 180,
                'baggage_allowance_kg' => 20,
                'refund_policy' => 'Bisa refund dengan potongan 10% sebelum keberangkatan',
            ]);

            // CGK → JOG (Jakarta - Yogyakarta)
            Flight::firstOrCreate([
                'airline_id' => $batik->id,
                'flight_number' => 'ID7777',
                'departure_airport_id' => $cgk->id,
                'arrival_airport_id' => $jog->id,
                'departure_time' => $date->copy()->setTime(13, 0),
            ], [
                'airplane_id' => $pkLbs->id,
                'arrival_time' => $date->copy()->setTime(14, 30),
                'price' => 750000,
                'available_seats' => 140,
                'duration_minutes' => 90,
                'baggage_allowance_kg' => 20,
                'refund_policy' => 'Bisa refund dengan potongan 15% sebelum keberangkatan',
            ]);
        }

        $this->command->info('✅ ' . Flight::count() . ' jadwal penerbangan dibuat');

        $this->command->info('');
        $this->command->info('🎉 SEEDING SELESAI!');
        $this->command->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->command->info(' Ringkasan Data:');
        $this->command->info('   • Maskapai: ' . Airline::count());
        $this->command->info('   • Bandara: ' . Airport::count());
        $this->command->info('   • Pesawat: ' . Airplane::count());
        $this->command->info('   • Jadwal Penerbangan: ' . Flight::count());
        $this->command->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->command->info('');
        $this->command->info('💡 Sekarang customer bisa:');
        $this->command->info('   1. Lihat jadwal penerbangan yang tersedia');
        $this->command->info('   2. Cari penerbangan berdasarkan rute');
        $this->command->info('   3. Booking tiket dengan data yang lengkap');
    }
}