<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class InitialConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::create(['name' => 'super usuario']);

        $user = User::create([
            'name' => config('owner-system.user.name'),
            'email' => config('owner-system.user.email'),
            'password' => Hash::make(config('owner-system.user.password')),
        ]);
        $user->assignRole($role);
    }
}
