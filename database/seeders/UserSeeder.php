<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder {
    public function run(): void {
        User::create([
            'name'      => 'Admin Principal',
            'email'     => 'admin@club.com',
            'password'  => Hash::make('password123'),
            'role'      => 'admin',
            'telephone' => '0600000000',
            'is_active' => true,
        ]);

        User::create([
            'name'      => 'Coach Ahmed',
            'email'     => 'coach@club.com',
            'password'  => Hash::make('password123'),
            'role'      => 'coach',
            'telephone' => '0611111111',
            'is_active' => true,
        ]);

        User::create([
            'name'      => 'Parent Youssef',
            'email'     => 'parent@club.com',
            'password'  => Hash::make('password123'),
            'role'      => 'parent',
            'telephone' => '0622222222',
            'is_active' => true,
        ]);
    }
}