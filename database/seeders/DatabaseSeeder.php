<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        Role::create([
            'role_name' => 'admin'
        ]);

        Role::create([
            'role_name' => 'karyawan'
        ]);

        Role::create([
            'role_name' => 'pelanggan'
        ]);

        User::create([
            'nama' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin'),
            'role_id' => 1,
            'alamat' => 'Padang, Sumatera Barat',
            'telepon' => '08123456789'
        ]);

        User::create([
            'nama' => 'Karyawan',
            'email' => 'karyawan@gmail.com',
            'password' => bcrypt('karyawan'),
            'role_id' => 2,
            'alamat' => 'Padang, Sumatera Barat',
            'telepon' => '08123456789'
        ]);

        User::create([
            'nama' => 'Pelanggan',
            'email' => 'pelanggan@gmail.com',
            'password' => bcrypt('pelanggan'),
            'role_id' => 3,
            'alamat' => 'Padang, Sumatera Barat',
            'telepon' => '08123456789'
        ]);
    }
}
