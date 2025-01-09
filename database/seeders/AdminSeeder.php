<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin;

class AdminSeeder extends Seeder
{
    /**
    * Run the database seeds.
    *
    * @return void
    */
    public function run()
    {
        Admin::create([
            'name' => 'admin',
            'email' => 'admin@abridge-co.jp',
            'password' => 'P@ssw0rd',
        ]);
    }
}
