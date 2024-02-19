<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::create(['name' => 'admin']);

        User::factory()->create([
            'name' => 'Pepe',
            'email' => 'pepe@mail.es',
            'password' => bcrypt('123456'),
        ])->assignRole('admin');

        User::factory()->create([
            'name' => 'User',
            'email' => 'user@mail.es',
            'password' => bcrypt('123456'),
        ]);

        User::factory()->create([
            'name' => 'Carlos Abrisqueta',
            'email' => 'carlos@test.com',
        ])->assignRole('admin');

        User::factory(100)->create();
    }
}
