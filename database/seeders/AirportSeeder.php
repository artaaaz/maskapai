<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Airport;

class AirportSeeder extends Seeder
{
    public function run(): void
    {
        $airports = [
            ['name' => 'Soekarno-Hatta International Airport', 'city' => 'Jakarta', 'country' => 'Indonesia', 'iata_code' => 'CGK', 'terminal' => '1,2,3', 'timezone' => 'Asia/Jakarta'],
            ['name' => 'Halim Perdanakusuma International Airport', 'city' => 'Jakarta', 'country' => 'Indonesia', 'iata_code' => 'HLP', 'terminal' => '1', 'timezone' => 'Asia/Jakarta'],
            ['name' => 'Juanda International Airport', 'city' => 'Surabaya', 'country' => 'Indonesia', 'iata_code' => 'SUB', 'terminal' => '1,2', 'timezone' => 'Asia/Jakarta'],
            ['name' => 'Ngurah Rai International Airport', 'city' => 'Denpasar', 'country' => 'Indonesia', 'iata_code' => 'DPS', 'terminal' => 'International,Domestic', 'timezone' => 'Asia/Makassar'],
            ['name' => 'Sultan Hasanuddin International Airport', 'city' => 'Makassar', 'country' => 'Indonesia', 'iata_code' => 'UPG', 'terminal' => '1,2', 'timezone' => 'Asia/Makassar'],
            ['name' => 'Kualanamu International Airport', 'city' => 'Medan', 'country' => 'Indonesia', 'iata_code' => 'KNO', 'terminal' => '1', 'timezone' => 'Asia/Jakarta'],
            ['name' => 'Husein Sastranegara International Airport', 'city' => 'Bandung', 'country' => 'Indonesia', 'iata_code' => 'BDO', 'terminal' => '1', 'timezone' => 'Asia/Jakarta'],
            ['name' => 'Yogyakarta International Airport', 'city' => 'Yogyakarta', 'country' => 'Indonesia', 'iata_code' => 'YIA', 'terminal' => '1', 'timezone' => 'Asia/Jakarta'],
            ['name' => 'Adi Soemarmo International Airport', 'city' => 'Surakarta', 'country' => 'Indonesia', 'iata_code' => 'SOC', 'terminal' => '1', 'timezone' => 'Asia/Jakarta'],
            ['name' => 'Ahmad Yani International Airport', 'city' => 'Semarang', 'country' => 'Indonesia', 'iata_code' => 'SRG', 'terminal' => '1', 'timezone' => 'Asia/Jakarta'],
            ['name' => 'Sultan Mahmud Badaruddin II International Airport', 'city' => 'Palembang', 'country' => 'Indonesia', 'iata_code' => 'PLM', 'terminal' => '1', 'timezone' => 'Asia/Jakarta'],
            ['name' => 'Sultan Syarif Kasim II International Airport', 'city' => 'Pekanbaru', 'country' => 'Indonesia', 'iata_code' => 'PKU', 'terminal' => '1', 'timezone' => 'Asia/Jakarta'],
            ['name' => 'Sepinggan International Airport', 'city' => 'Balikpapan', 'country' => 'Indonesia', 'iata_code' => 'BPN', 'terminal' => '1,2', 'timezone' => 'Asia/Makassar'],
            ['name' => 'Supadio International Airport', 'city' => 'Pontianak', 'country' => 'Indonesia', 'iata_code' => 'PNK', 'terminal' => '1', 'timezone' => 'Asia/Jakarta'],
            ['name' => 'Lombok International Airport', 'city' => 'Mataram', 'country' => 'Indonesia', 'iata_code' => 'LOP', 'terminal' => '1', 'timezone' => 'Asia/Makassar'],
            ['name' => 'Sultan Babullah Airport', 'city' => 'Ternate', 'country' => 'Indonesia', 'iata_code' => 'TTE', 'terminal' => '1', 'timezone' => 'Asia/Jayapura'],
            ['name' => 'Moses Kilangin Airport', 'city' => 'Timika', 'country' => 'Indonesia', 'iata_code' => 'TIM', 'terminal' => '1', 'timezone' => 'Asia/Jayapura'],
            ['name' => 'Sentani International Airport', 'city' => 'Jayapura', 'country' => 'Indonesia', 'iata_code' => 'DJJ', 'terminal' => '1', 'timezone' => 'Asia/Jayapura'],
            ['name' => 'Pattimura International Airport', 'city' => 'Ambon', 'country' => 'Indonesia', 'iata_code' => 'AMQ', 'terminal' => '1', 'timezone' => 'Asia/Jayapura'],
            ['name' => 'Sam Ratulangi International Airport', 'city' => 'Manado', 'country' => 'Indonesia', 'iata_code' => 'MDC', 'terminal' => '1', 'timezone' => 'Asia/Makassar'],
            ['name' => 'Minangkabau International Airport', 'city' => 'Padang', 'country' => 'Indonesia', 'iata_code' => 'PDG', 'terminal' => '1', 'timezone' => 'Asia/Jakarta'],
            ['name' => 'Raja Haji Fisabilillah Airport', 'city' => 'Tanjung Pinang', 'country' => 'Indonesia', 'iata_code' => 'TNJ', 'terminal' => '1', 'timezone' => 'Asia/Jakarta'],
            ['name' => 'Hang Nadim International Airport', 'city' => 'Batam', 'country' => 'Indonesia', 'iata_code' => 'BTH', 'terminal' => '1', 'timezone' => 'Asia/Jakarta'],
            ['name' => 'Depati Amir Airport', 'city' => 'Pangkal Pinang', 'country' => 'Indonesia', 'iata_code' => 'PGK', 'terminal' => '1', 'timezone' => 'Asia/Jakarta'],
            ['name' => 'Radin Inten II International Airport', 'city' => 'Bandar Lampung', 'country' => 'Indonesia', 'iata_code' => 'TKG', 'terminal' => '1', 'timezone' => 'Asia/Jakarta'],
            ['name' => 'Syamsudin Noor International Airport', 'city' => 'Banjarmasin', 'country' => 'Indonesia', 'iata_code' => 'BDJ', 'terminal' => '1', 'timezone' => 'Asia/Makassar'],
            ['name' => 'Jenderal Ahmad Yani Airport', 'city' => 'Semarang', 'country' => 'Indonesia', 'iata_code' => 'SRG', 'terminal' => '1', 'timezone' => 'Asia/Jakarta'],
            ['name' => 'Frans Sales Lega Airport', 'city' => 'Ruteng', 'country' => 'Indonesia', 'iata_code' => 'RTG', 'terminal' => '1', 'timezone' => 'Asia/Makassar'],
            ['name' => 'Mutiara SIS Al-Jufrie Airport', 'city' => 'Palu', 'country' => 'Indonesia', 'iata_code' => 'PLW', 'terminal' => '1', 'timezone' => 'Asia/Makassar'],
            ['name' => 'Sultan Aji Muhammad Sulaiman Airport', 'city' => 'Balikpapan', 'country' => 'Indonesia', 'iata_code' => 'BPN', 'terminal' => '1,2', 'timezone' => 'Asia/Makassar'],
            ['name' => 'Tjilik Riwut Airport', 'city' => 'Palangkaraya', 'country' => 'Indonesia', 'iata_code' => 'PKY', 'terminal' => '1', 'timezone' => 'Asia/Jakarta'],
            ['name' => 'El Tari International Airport', 'city' => 'Kupang', 'country' => 'Indonesia', 'iata_code' => 'KOE', 'terminal' => '1', 'timezone' => 'Asia/Makassar'],
            ['name' => 'Komodo Airport', 'city' => 'Labuan Bajo', 'country' => 'Indonesia', 'iata_code' => 'LBJ', 'terminal' => '1', 'timezone' => 'Asia/Makassar'],
            ['name' => 'Haluoleo Airport', 'city' => 'Kendari', 'country' => 'Indonesia', 'iata_code' => 'KDI', 'terminal' => '1', 'timezone' => 'Asia/Makassar'],
            ['name' => 'Juwata International Airport', 'city' => 'Tarakan', 'country' => 'Indonesia', 'iata_code' => 'TRK', 'terminal' => '1', 'timezone' => 'Asia/Makassar'],
        ];

        foreach ($airports as $airport) {
            Airport::updateOrCreate(
                ['iata_code' => $airport['iata_code']],
                $airport
            );
        }
    }
}