<?php

namespace Tests\Feature\Admin;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SuperAdminTest extends TestCase
{
    use RefreshDatabase;


    public function test_only_super_admin_can_access_following_tests()
    {
        $this->postJson(route('roles-addRole'))->assertSee('Unauthenticated.');
        $this->getJson(route('roles-all'))->assertSee('Unauthenticated.');
        $this->getJson(route('roles-oneRole', 2))->assertSee('Unauthenticated.');
        $this->patchJson(route('roles-updateRole', 2))->assertSee('Unauthenticated.');
        $this->deleteJson(route('roles-deleteRole', 2))->assertSee('Unauthenticated.');
        $this->getJson(route('permissions-all'))->assertSee('Unauthenticated.');
        $this->postJson(route('admins-add'))->assertSee('Unauthenticated.');
        $this->patchJson(route('admins-update', 2))->assertSee('Unauthenticated.');
        $this->deleteJson(route('admins-delete', 2))->assertSee('Unauthenticated.');
    }

    public function test_super_admin_can_define_role()
    {
        Artisan::call('db:seed');
        $response = $this->postJson(route('login'), [
            'username' => 'sina',
            'password' => 'nnn'
        ]);

        $this->postJson(route('roles-addRole'), [
            'role' => 'admin',
            'permissions' => [1, 2, 3],
        ], ['authorization' => 'Bearer ' . $response['token']])->assertSee('Role created');

        $this->assertDatabaseCount('roles', 2);
        $this->assertDatabaseCount('role_permission', 3);

    }

    public function test_super_admin_can_see_all_roles_with_permissions()
    {
        Artisan::call('db:seed');

        $role = Role::factory()->create();
        $role->permissions()->attach([1, 2, 3]);

        $Role = Role::factory()->create(['name' => 'tester']);
        $Role->permissions()->attach([4, 5, 6]);

        $response = $this->postJson(route('login'), [
            'username' => 'sina',
            'password' => 'nnn'
        ]);
        $this->getJson(route('roles-all'), ['authorization' => 'Bearer ' . $response['token']])
            ->assertJson([
                ['name' => 'super_admin'],
                ['name' => 'admin', 'permissions' => [['name' => 'show_post'], ['name' => 'delete_post'], ['name' => 'confirm_post']]],
                ['name' => 'tester', 'permissions' => [['name' => 'add_category'], ['name' => 'delete_category'], ['name' => 'update_category']]],
            ]);
    }

    public function test_super_admin_can_see_one_role_with_permissions()
    {
        Artisan::call('db:seed');

        $role = Role::factory()->create();
        $role->permissions()->attach([1, 2, 3]);

        $Role = Role::factory()->create(['name' => 'tester']);
        $Role->permissions()->attach([4, 5, 6]);

        $response = $this->postJson(route('login'), [
            'username' => 'sina',
            'password' => 'nnn'
        ]);

        $this->getJson(route('roles-oneRole', 2), ['authorization' => 'Bearer ' . $response['token']])->assertJson(
            ['name' => 'admin', 'permissions' => [['name' => 'show_post'], ['name' => 'delete_post'], ['name' => 'confirm_post']],
            ]);
    }

    public function test_super_admin_can_update_role_with_its_permissions()
    {
        Artisan::call('db:seed');

        $role = Role::factory()->create();
        $role->permissions()->attach([1, 2, 3]);

        $Role = Role::factory()->create(['name' => 'tester']);
        $Role->permissions()->attach([4, 5, 6]);

        $response = $this->postJson(route('login'), [
            'username' => 'sina',
            'password' => 'nnn'
        ]);

        $this->patchJson(route('roles-updateRole', 2), [
            'name' => 'adminoo',
            'permissions' => [3],
        ], ['authorization' => 'Bearer ' . $response['token']])
            ->assertJson([['name' => 'adminoo', 'permissions' => [['name' => 'confirm_post']]]])
            ->assertDontSee('show_post');
    }

    public function test_super_admin_can_not_chane_super_admin_role()
    {
        Artisan::call('db:seed');
        $response = $this->postJson(route('login'), [
            'username' => 'sina',
            'password' => 'nnn'
        ]);

        $this->patchJson(route('roles-updateRole', 1), [
            'name' => 'adminoo',
            'permissions' => [3],
        ], ['authorization' => 'Bearer ' . $response['token']])
            ->assertSee('you can not change the SUPER_ADMIN role');
    }

    public function test_super_admin_can_delete_role()
    {
        Artisan::call('db:seed');

        $role = Role::factory()->create();
        $role->permissions()->attach([1, 2, 3]);

        $Role = Role::factory()->create(['name' => 'tester']);
        $Role->permissions()->attach([4, 5, 6]);

        $response = $this->postJson(route('login'), [
            'username' => 'sina',
            'password' => 'nnn'
        ]);

        $this->deleteJson(route('roles-deleteRole', 2), [], ['authorization' => 'Bearer ' . $response['token']])
            ->assertSee('role deleted');
    }


    public function test_super_admin_can_see_all_permissions()
    {
        Artisan::call('db:seed');
        $response = $this->postJson(route('login'), [
            'username' => 'sina',
            'password' => 'nnn'
        ]);

        $this->getJson(route('permissions-all'), ['authorization' => 'Bearer ' . $response['token']])
            ->assertSee(['confirm_post', 'add_category', 'delete_comment']);
    }


    public function test_super_admin_can_define_admin_with_role()
    {
        Artisan::call('db:seed');

        $role = Role::factory()->create();
        $role->permissions()->attach([1, 2, 3]);

        $response = $this->postJson(route('login'), [
            'username' => 'sina',
            'password' => 'nnn'
        ]);

        $this->assertDatabaseCount('user_role', 1);

        $this->postJson(route('admins-add'), [
            'name' => 'Sky',
            'username' => 'sky',
            'role' => '2'
        ], ['authorization' => 'Bearer ' . $response['token']])->assertSee('User created successfully');
        $this->assertDatabaseCount('user_role', 2);
    }

    public function test_super_admin_can_add_role_to_existing_user()
    {
        Artisan::call('db:seed');

        $role = Role::factory()->create();
        $role->permissions()->attach([1, 2, 3]);

        $response = $this->postJson(route('login'), [
            'username' => 'sina',
            'password' => 'nnn'
        ]);

        User::factory()->create(['username' => 'sky']);

        $this->patchJson(route('admins-update', 2), [
            'role' => 2
        ], ['authorization' => 'Bearer ' . $response['token']])->assertSee('The role was successfully created');
        $this->assertDatabaseCount('user_role', 2);
    }

    public function test_super_admin_can_not_add_existing_role_to_existing_user()
    {
        Artisan::call('db:seed');

        $role = Role::factory()->create();
        $role->permissions()->attach([1, 2, 3]);

        $response = $this->postJson(route('login'), [
            'username' => 'sina',
            'password' => 'nnn'
        ]);

        User::factory()->create(['username' => 'sky']);

        $this->patchJson(route('admins-update', 2), [
            'role' => 2
        ], ['authorization' => 'Bearer ' . $response['token']]);
        $this->patchJson(route('admins-update', 2), [
            'role' => 2
        ], ['authorization' => 'Bearer ' . $response['token']])->assertSee('Role name already exists');
    }

    public function test_super_admin_can_remove_role_from_admin()
    {
        $this->test_super_admin_can_add_role_to_existing_user();
        $response = $this->postJson(route('login'), [
            'username' => 'sina',
            'password' => 'nnn'
        ]);
        $this->deleteJson(route('admins-delete', 2), [], ['authorization' => 'Bearer ' . $response['token']])
            ->assertSee('The role was successfully removed');
        $this->assertDatabaseCount('user_role', 1);
    }
}



