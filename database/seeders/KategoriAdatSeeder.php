<?php

namespace Database\Seeders;

use App\Models\KategoriAdat;
use Illuminate\Database\Seeder;

class KategoriAdatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        KategoriAdat::insert([
            [
                'nama_kategori' => 'Adat Jawa',
            ],
            [
                'nama_kategori' => 'Adat Toraja',
            ],
            [
                'nama_kategori' => 'Adat Sunda',
            ],
            [
                'nama_kategori' => 'Adat Palembang',
            ],
            [
                'nama_kategori' => 'Adat Aceh',
            ],
            [
                'nama_kategori' => 'Adat Bugis',
            ],
            [
                'nama_kategori' => 'Adat Batak',
            ],

        ]);
    }
}
