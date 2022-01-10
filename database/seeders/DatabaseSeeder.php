<?php

namespace Database\Seeders;

use App\Models\KategoriAdat;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call(ProductCategorySeeder::class);
        $this->call(UserSeeder::class);
        $this->call(BankAccountSeeder::class);
        $this->call(TemaSeeder::class);

    }
}
