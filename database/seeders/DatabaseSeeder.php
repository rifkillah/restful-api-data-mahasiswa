<?php

namespace Database\Seeders;

use App\Models\Mahasiswa;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Mahasiswa::firstOrCreate(
            ['nim' => '2210114001'],
            [
                'nama' => 'Budi Santoso',
                'jurusan' => 'Teknik Informatika',
                'ipk' => 3.85,
            ]
        );

        Mahasiswa::firstOrCreate(
            ['nim' => '2210114002'],
            [
                'nama' => 'Siti Aminah',
                'jurusan' => 'Sistem Informasi',
                'ipk' => 3.92,
            ]
        );

        Mahasiswa::firstOrCreate(
            ['nim' => '2210114003'],
            [
                'nama' => 'Andi Pratama',
                'jurusan' => 'Teknik Informatika',
                'ipk' => 3.78,
            ]
        );
    }
}
