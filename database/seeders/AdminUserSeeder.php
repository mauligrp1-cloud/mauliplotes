<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::firstOrCreate(
            ['slug' => 'admin'],
            ['name' => 'Administrator', 'description' => 'Full administrative access']
        );

        $user = User::updateOrCreate(
            ['email' => 'admin@mauliproperties.test'],
            [
                'name' => 'Mauli Administrator',
                'password' => Hash::make('Mauli@12345'),
            ]
        );
        $user->roles()->syncWithoutDetaching([$role->id]);

        $user2 = User::updateOrCreate(
            ['email' => 'admin@mauliplots.in'],
            [
                'name' => 'Mauli Administrator',
                'password' => Hash::make('Mauli@12345'),
            ]
        );
        $user2->roles()->syncWithoutDetaching([$role->id]);
    }
}
