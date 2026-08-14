<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use App\Enums\AdminStatus;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::firstOrCreate([['email' => 'admin@gmail.com']],[
            'name' => 'Super Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('Chopup12..'),
            'status' => AdminStatus::Active->value,
        ]);
    }
}
