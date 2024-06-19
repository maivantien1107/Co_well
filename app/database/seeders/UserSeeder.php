<?php

namespace Database\Seeders;

use App\Models\Role;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('role_users')->truncate();
        DB::table('roles')->truncate();
        DB::table('users')->truncate();
        $roles = [
            [
                'name' => 'Admin',
                'slug' => 'admin',
            ],
            [
                'name' => 'User',
                'slug' => 'user',
            ],
            [
                'name' => 'SuperAdmin',
                'slug' => 'superadmin',
            ],
        ];

        Role::insert($roles);

        $users = [
            [
                'name' => fake()->name(),
                'phone' => fake()->phoneNumber(),
                'username'=>fake()->name(),
                'sex'=>'1',
                'username'=>fake()->name(),
                'email' => 'admin@example.com',
            ],
            [
                'name' => fake()->name(),
                'phone' => fake()->phoneNumber(),
                'username'=>fake()->name(),
                'sex'=>'1',
                'username'=>fake()->name(),
                'email' => 'super@example.com',
            ],
            [
                'name' => fake()->name(),
                'phone' => fake()->phoneNumber(),
                'username'=>fake()->name(),
                'sex'=>'1',
                'username'=>fake()->name(),
                'email' => 'user@example.com',
            ],
        ];

        foreach ($users as $userItem) {
            // $user  =  \App\Models\User::factory()->create($userItem);
            $userItem['password'] = '1234567@';
            $user = Sentinel::registerAndActivate($userItem);
            switch ($userItem['email']) {
                case 'admin@example.com':
                    $role = Sentinel::findRoleBySlug('admin');
                    $role->users()->attach($user);
                    break;
                case 'super@example.com':
                    $role = Sentinel::findRoleBySlug('superadmin');
                    $role->users()->attach($user);
                    break;
                case 'user@example.com':
                    $role = Sentinel::findRoleBySlug('user');
                    $role->users()->attach($user);
                    break;
            }
        }
    }

    
}