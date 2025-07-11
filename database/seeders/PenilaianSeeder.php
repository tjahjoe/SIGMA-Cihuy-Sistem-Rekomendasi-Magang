<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PenilaianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'id_magang' => '2',
                'fasilitas' => '4',  
                'tugas' => '5',      
                'pembinaan' => '4',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_magang' => '3',
                'fasilitas' => '1',  
                'tugas' => '5',      
                'pembinaan' => '2',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_magang' => '5',
                'fasilitas' => '3',  
                'tugas' => '4',      
                'pembinaan' => '2',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_magang' => '6',
                'fasilitas' => '1',  
                'tugas' => '5',      
                'pembinaan' => '3',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_magang' => '7',
                'fasilitas' => '4',  
                'tugas' => '2',      
                'pembinaan' => '5',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_magang' => '8',
                'fasilitas' => '4',  
                'tugas' => '5',      
                'pembinaan' => '4',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_magang' => '10',
                'fasilitas' => '4',  
                'tugas' => '4',      
                'pembinaan' => '3',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_magang' => '11',
                'fasilitas' => '4',  
                'tugas' => '3',      
                'pembinaan' => '3',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        DB::table('penilaian')->insert($data);
    }
}
