<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::factory()
            ->has(Role::factory()->count(3), 'roles')
            ->create();

        $role = Role::factory()
            ->has(User::factory()->count(3), 'users')
            ->create();
    }
}
