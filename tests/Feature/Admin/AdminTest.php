<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;


    public function test_delete_post_permission_can_delete_all_users_posts()
    {
        Artisan::call('db:seed');
        User::factory()->has(Role::factory())->create(['username' => 'sky']);
        Post::factory()->count(3)->create();

        $response = $this->postJson(route('login'), [
            'username' => 'sina',
            'password' => 'nnn'
        ]); // ['authorization' => 'Bearer ' . $response['token']]

        $this->assertDatabaseCount('posts', 3);
        $this->deleteJson(route('posts-deletePost', 1), [], ['authorization' => 'Bearer ' . $response['token']]);
        $this->assertDatabaseCount('posts', 2);
    }

    public function test_Delete_comment_permission_can_delete_all_users_comments()
    {
        Artisan::call('db:seed');
        Post::factory()->count(2)->has(Comment::factory()->count(3))->create();
        $response = $this->postJson(route('login'), [
            'username' => 'sina',
            'password' => 'nnn'
        ]);
        $this->assertDatabaseCount('comments', 6);
        $this->deleteJson(route('comments-deleteComment', 2), [], ['authorization' => 'Bearer ' . $response['token']]);
        $this->assertDatabaseCount('comments', 5);
    }


    public function test_permission_show_post_can_see_pending_posts()
    {
        Artisan::call('db:seed');
        Post::factory()->count(2)->create();
        Post::factory()->create(['status' => 'accepted']);
        $response = $this->postJson(route('login'), ['username' => 'sina', 'password' => 'nnn']);
        $this->getJson(route('posts-pending'), ['authorization' => 'Bearer ' . $response['token']])
            ->assertJsonStructure(['data' => [['id', 'title', 'status']]])->assertDontSee('accepted');
    }

    public function test_permission_show_post_can_see_posts_status()
    {
        Artisan::call('db:seed');
        Post::factory()->count(2)->create();
        Post::factory()->create(['status' => 'accepted']);
        $response = $this->postJson(route('login'), ['username' => 'sina', 'password' => 'nnn']);
        $this->getJson(route('posts-status', 2), ['authorization' => 'Bearer ' . $response['token']])
            ->assertJsonFragment(['status' => 'pending']);
        $this->getJson(route('posts-status', 3), ['authorization' => 'Bearer ' . $response['token']])
            ->assertJsonFragment(['status' => 'accepted']);
    }

    public function test_permission_confirm_post_can_confirm_posts()
    {
        Artisan::call('db:seed');
        Post::factory()->count(2)->create();
        $response = $this->postJson(route('login'), ['username' => 'sina', 'password' => 'nnn']);
        $this->patchJson(route('posts-confirm', 1), ['status' => 'accepted'], ['authorization' => 'Bearer ' . $response['token']])
            ->assertSee('post accepted');
        $this->assertDatabaseHas('posts', ['status' => 'accepted']);
    }

    public function test_everyone_can_see_all_categories()
    {
        Category::factory()->count(2)->create();
        $this->getJson(route('categories-all'))->assertJson([
            ['id' => 1, 'title' => 'C1'],
            ['id' => 2, 'title' => 'C1'],
        ]);
    }

    public function test_admins_can_add_categories()
    {
        Artisan::call('db:seed');
        $response = $this->postJson(route('login'), ['username' => 'sina', 'password' => 'nnn']);
        $this->postJson(route('categories-add'), ['category' => 'CC'], ['authorization' => 'Bearer ' . $response['token']])
            ->assertSee('category created');
        $this->assertDatabaseCount('categories', 1);
    }

    public function test_admins_can_delete_category()
    {
        Artisan::call('db:seed');
        Category::factory()->count(2)->create();
        $response = $this->postJson(route('login'), ['username' => 'sina', 'password' => 'nnn']);
        $this->assertDatabaseCount('categories', 2);
        $this->deleteJson(route('categories-delete', 1), [], ['authorization' => 'Bearer ' . $response['token']])
            ->assertSee('category deleted');
        $this->assertDatabaseCount('categories', 1);
    }

    public function test_if_a_category_has_posts_nobody_can_delete_it()
    {
        Artisan::call('db:seed');
        Post::factory()->count(2)->create();
        $response = $this->postJson(route('login'), ['username' => 'sina', 'password' => 'nnn']);
        $this->assertDatabaseCount('categories', 2);
        $this->deleteJson(route('categories-delete', 1), [], ['authorization' => 'Bearer ' . $response['token']])
            ->assertSee('the category has posts so it can not be deleted');
        $this->assertDatabaseCount('categories', 2);
    }

    public function test_admins_can_update_category()
    {
        Artisan::call('db:seed');
        Category::factory()->count(2)->create();
        $response = $this->postJson(route('login'), ['username' => 'sina', 'password' => 'nnn']);
        $this->patchJson(route('categories-update', 1), [
            'category' => 'CCC',
        ], ['authorization' => 'Bearer ' . $response['token']])->assertSee('category updated');
        $this->assertDatabaseHas('categories',['category' => 'CCC']);
    }
}


