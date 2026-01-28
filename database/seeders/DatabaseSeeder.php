<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\Pupuk;
use App\Models\PupukGambar;
use App\Models\UserData;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
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

        $faker = Faker::create('id_ID');
        for ($i = 1; $i <= 3; $i++) {
            $user = User::create([
                'email' => $faker->unique()->safeEmail(),
                'password' => Hash::make('karyawan123'),
                'role_id' => 2,
            ]);

            UserData::create([
                'user_id' => $user->user_id ?? $user->id,
                'nama' => $faker->name(),
                'alamat' => $faker->address(),
                'telepon' => $faker->phoneNumber(),
            ]);
        }

        for ($i = 1; $i <= 10; $i++) {
            $user = User::create([
                'email' => $faker->unique()->safeEmail(),
                'password' => Hash::make('pelanggan123'),
                'role_id' => 3,
            ]);

            UserData::create([
                'user_id' => $user->user_id ?? $user->id,
                'nama' => $faker->name(),
                'alamat' => $faker->address(),
                'telepon' => $faker->phoneNumber(),
            ]);
        }

        $dataPupuk = [
            [
                'nama' => 'Pupuk Organik Serbuk',
                'jenis' => 'Organik',
                'berat' => 50,
                'stok' => 100,
                'harga' => 65000,
                'deskripsi' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Illum nam hic, nulla voluptas tempore quaerat alias veritatis earum dignissimos voluptates!',
                'unggulan' => true,
                'gambar' => [
                    'pupuk_organik_serbuk1.jpg',
                    'pupuk_organik_serbuk2.png',
                ],
            ],
            [
                'nama' => 'Pupuk Organik Granul',
                'jenis' => 'Organik',
                'berat' => 50,
                'stok' => 100,
                'harga' => 75000,
                'deskripsi' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Illum nam hic, nulla voluptas tempore quaerat alias veritatis earum dignissimos voluptates!',
                'unggulan' => false,
                'gambar' => [
                    'pupuk_organik_granul1.jpg',
                    'pupuk_organik_granul2.png',
                ],
            ],
        ];

        foreach ($dataPupuk as $item) {
            $pupuk = Pupuk::create([
                'nama' => $item['nama'],
                'slug' => Str::slug($item['nama']),
                'jenis' => $item['jenis'],
                'berat' => $item['berat'],
                'stok' => $item['stok'],
                'deskripsi' => $item['deskripsi'],
                'harga' => $item['harga'],
                'unggulan' => $item['unggulan'],
            ]);

            foreach ($item['gambar'] as $gambar) {
                $sourcePath = database_path('seeders/images/' . $gambar);

                if (file_exists($sourcePath)) {
                    Storage::disk('public')->put(
                        'pupuk/' . $gambar,
                        file_get_contents($sourcePath)
                    );
                }

                PupukGambar::create([
                    'pupuk_id' => $pupuk->pupuk_id,
                    'gambar_url' => 'pupuk/' . $gambar,
                ]);
            }
        }
    }
}
