<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\Pupuk;
use App\Models\Produksi;
use App\Models\UserData;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

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

        $dataPupuk = [
            [
                'nama' => 'Pupuk Organik Serbuk',
                'jenis' => 'Organik',
                'berat' => 50,
                'stok' => 100,
                'harga' => 65000,
                'gambar' => 'pupuk_organik_serbuk.jpg',
            ],
            [
                'nama' => 'Pupuk Organik Granul',
                'jenis' => 'Organik',
                'berat' => 50,
                'stok' => 80,
                'harga' => 75000,
                'gambar' => 'pupuk_organik_granul.jpg',
            ],
        ];

        foreach ($dataPupuk as $pupuk) {
            $sourcePath = database_path('seeders/images/' . $pupuk['gambar']);

            if (file_exists($sourcePath)) {
                Storage::disk('public')->put('pupuk/' . $pupuk['gambar'], file_get_contents($sourcePath));
            }

            Pupuk::create([
                'nama' => $pupuk['nama'],
                'slug' => Str::slug($pupuk['nama']),
                'jenis' => $pupuk['jenis'],
                'berat' => $pupuk['berat'],
                'stok' => $pupuk['stok'],
                'harga' => $pupuk['harga'],
                'gambar' => 'pupuk/' . $pupuk['gambar'],
            ]);
        }
    }
}
