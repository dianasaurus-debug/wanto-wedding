<?php

namespace Database\Seeders;

use App\Models\BankAccount;
use Illuminate\Database\Seeder;

class BankAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BankAccount::insert([
            [
                'nama_bank' => 'Bank BNI',
                'nomor_rekening' => '19189118918918',
                'acc_holder' => 'Wanto'
            ],
            [
                'nama_bank' => 'Bank Mandiri',
                'nomor_rekening' => '17981918918191',
                'acc_holder' => 'Wanto'
            ],
            [
                'nama_bank' => 'Bank BRI',
                'nomor_rekening' => '19891891891891',
                'acc_holder' => 'Wanto'
            ],
        ]);
    }
}
