<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\UserData;
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

        $admin = User::create([
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin'),
            'role_id' => 1,
        ]);

        UserData::create([
            'user_id' => $admin->user_id,
            'nama' => 'Admin',
            'alamat' => 'Padang, Sumatera Barat',
            'telepon' => '08123456789'
        ]);

        $karyawan = User::create([
            'email' => 'karyawan@gmail.com',
            'password' => bcrypt('karyawan'),
            'role_id' => 2,
        ]);

        UserData::create([
            'user_id' => $karyawan->user_id,
            'nama' => 'Karyawan',
            'alamat' => 'Padang, Sumatera Barat',
            'telepon' => '08123456789'
        ]);

        $pelanggan = User::create([
            'email' => 'pelanggan@gmail.com',
            'password' => bcrypt('pelanggan'),
            'role_id' => 3,
        ]);

        UserData::create([
            'user_id' => $pelanggan->user_id,
            'nama' => 'Pelanggan',
            'alamat' => 'Padang, Sumatera Barat',
            'telepon' => '08123456789'
        ]);
    }
}
