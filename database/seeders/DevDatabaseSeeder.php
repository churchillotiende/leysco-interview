<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class DevDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::query()->insert([
            ['name' => 'Admin'],
            ['name' => 'Sales'],            
        ]);

        User::query()->insert([
            ['name' => 'Leysco Admin','email'=>'admin@leysco.org','role_id'=>1,'password'=> Hash::make('password')],
            ['name' => 'Leysco Sales','email'=>'salesn@leysco.org','role_id'=>2,'password'=>Hash::make('password')],
        ]);

        Product::factory(10)->create();
    }
}
