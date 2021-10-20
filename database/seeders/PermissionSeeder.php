<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Permission;


class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::query()->firstOrCreate([
            'name' => 'show_post',
        ]);

        Permission::query()->firstOrCreate([
            'name' => 'delete_post',
        ]);

        Permission::query()->firstOrCreate([
            'name' => 'confirm_post',
        ]);

        Permission::query()->firstOrCreate([
            'name' => 'add_category',
        ]);

        Permission::query()->firstOrCreate([
            'name' => 'delete_category',
        ]);

        Permission::query()->firstOrCreate([
            'name' => 'update_category',
        ]);

        Permission::query()->firstOrCreate([
            'name' => 'delete_comment',
        ]);


    }
}






