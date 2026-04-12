<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Administrateur',
            'email' => 'admin@demo.com',
            'password' => bcrypt('password'),
            'company' => 'Ma Société SARL',
            'phone' => '77 123 45 67',
            'address' => 'Dakar, Sénégal'
        ]);
    }
}
