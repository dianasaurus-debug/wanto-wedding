<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeederCustomer extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //customer
        User::create([
            'nama_depan' => 'Lara',
            'nama_belakang' => 'Croft',
            'is_admin' => 0,
            'email' => 'lara@gmail.com',
            'password' => Hash::make('password')
        ]);
        //customer
        User::create([
            'nama_depan' => 'Natasha',
            'nama_belakang' => 'Romanov',
            'is_admin' => 0,
            'email' => 'natasha@gmail.com',
            'password' => Hash::make('password')
        ]);
        //customer
        User::create([
            'nama_depan' => 'Ada',
            'nama_belakang' => 'Wong',
            'is_admin' => 0,
            'email' => 'ada@gmail.com',
            'password' => Hash::make('password')
        ]);
        //customer
        User::create([
            'nama_depan' => 'Jill',
            'nama_belakang' => 'Valentine',
            'is_admin' => 0,
            'email' => 'jill@gmail.com',
            'password' => Hash::make('password')
        ]);
        //customer
        User::create([
            'nama_depan' => 'Sherly',
            'nama_belakang' => 'Birkin',
            'is_admin' => 0,
            'email' => 'sherly@gmail.com',
            'password' => Hash::make('password')
        ]);
    }
}
