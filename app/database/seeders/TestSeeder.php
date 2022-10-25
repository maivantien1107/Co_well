<?php

namespace Database\Seeders;

use App\Models\User;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory()
        ->count(200)
        ->create();
        echo ('add role');
        $users=User::where('id','>',3)->get();
        $role = Sentinel::findRoleBySlug('user');
        foreach ($users as $user){          
                $role->users()->attach($user);
            
        }
    }
}
