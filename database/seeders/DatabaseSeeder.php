<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    // use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $superadminRole = Role::create([
            'uuid' => Str::uuid(),
            'nama_role' => 'superadmin',
            'status' => true,
        ]);

        $adminRole = Role::create([
            'uuid' => Str::uuid(),
            'nama_role' => 'admin',
            'status' => true,
        ]);

        $kasirRole = Role::create([
            'uuid' => Str::uuid(),
            'nama_role' => 'kasir',
            'status' => true,
        ]);

        User::create([
            'uuid' => Str::uuid(),
            'role_id' => $superadminRole->id,
            'username' => 'superadmin',
            'password' => Hash::make('password'),
            'nama' => 'Super Administrator',
            'status' => true,
        ]);

        User::create([
            'uuid' => Str::uuid(),
            'role_id' => $adminRole->id,
            'username' => 'admin',
            'password' => Hash::make('password'),
            'nama' => 'Administrator',
            'status' => true,
        ]);

        User::create([
            'uuid' => Str::uuid(),
            'role_id' => $kasirRole->id,
            'username' => 'kasir',
            'password' => Hash::make('password'),
            'nama' => 'Kasir 1',
            'status' => true,
        ]);

        $this->call(MasterDataSeeder::class);
    }
}
