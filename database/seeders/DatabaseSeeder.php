<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PermissionSeeder::class);


        $user = User::query()->Create([
            'name' => "Sina",
            'username' => 'sina',
            'image' => '#',
            'password' => bcrypt('nnn'),
        ]);

        $role = Role::query()->Create([
            'name' => 'super_admin',
        ]);

        $user->roles()->attach($role->id);



    }
}
