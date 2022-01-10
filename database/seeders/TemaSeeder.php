<?php

namespace Database\Seeders;

use App\Models\TemaKatalog;
use Illuminate\Database\Seeder;

class TemaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TemaKatalog::insert([
            [
                'nama_tema' => 'Adat',
            ],
            [
                'nama_tema' => 'Dekorasi Pernikahan',
            ],
            [
                'nama_tema' => 'Gaun Pernikahan',
            ],
            [
                'nama_tema' => 'Make Up Pengantin',
            ],

        ]);
    }
}
