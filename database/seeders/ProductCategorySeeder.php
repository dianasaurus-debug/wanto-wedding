<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProductCategory::insert([
           [
               'nama_kategori' => 'Paket Lengkap',
               'icon' => '',
           ],
            [
                'nama_kategori' => 'Make Up',
                'icon' => '',
            ],
            [
                'nama_kategori' => 'Catering',
                'icon' => '',
            ],
            [
                'nama_kategori' => 'Busana',
                'icon' => '',
            ],
        ]);
    }
}
