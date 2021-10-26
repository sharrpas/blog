<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    public function test_posts_count_must_zero()
    {
        $this->assertDatabaseCount('posts', 0);
    }

    public function test_user_can_see_their_posts()
    {
        $user = User::factory()->create();
        $this->assertDatabaseHas('users', [
            'name' => 'sina',
            'username' => 'sina',
        ]);

        $response = $this->postJson(route('login'), [
            'username' => 'sina',
            'password' => 'password'
        ]);
        $this->assertDatabaseCount('personal_access_tokens', 1);

        Post::factory()->count(2)->create();
        Post::factory()->state(['user_id' => '2'])->create();
        $this->assertDatabaseCount('posts', 3);

        $this->getJson(route('profile-posts-all'), [
            'authorization' => 'Bearer ' . $response['token']
        ])->assertJson(['data' => [[
            'title' => 'mmmm',
            'text' => 's mmmmmm',
        ]]]);
    }

    public function test_user_can_see_their_complete_post()
    {
        $user = User::factory()->create();
        $this->assertDatabaseHas('users', [
            'name' => 'sina',
            'username' => 'sina',
        ]);
        $response = $this->postJson(route('login'), [
            'username' => 'sina',
            'password' => 'password'
        ]);
        $this->assertDatabaseCount('personal_access_tokens', 1);

        Post::factory()->count(2)->create();
        Post::factory()->state(['user_id' => '2'])->create();
        $this->assertDatabaseCount('posts', 3);

        $this->getJson(route('profile-posts-onePost', ['post' => '1']), ['authorization' => 'Bearer ' . $response['token']])->assertJson([
            'id' => '1',
            'text' => 's mmmmmm',
        ]);
    }

    public function test_user_can_send_post()
    {
        User::factory()->create();
        $this->assertDatabaseHas('users', ['name' => 'sina', 'username' => 'sina',]);

        $response = $this->postJson(route('login'), ['username' => 'sina', 'password' => 'password']);
        $this->assertDatabaseCount('personal_access_tokens', 1);
        Category::factory()->create();
        $file = UploadedFile::fake()->image('p.jpg');

        $this->postJson(route('profile-posts-addPost', ['category' => 1]), [
            'title' => 'HI4550',
            'text' => '1234567891011ss',
            'image' => $file,
        ], ['authorization' => 'Bearer ' . $response['token']])->assertSee('Post created');
        $this->assertDatabaseCount('posts', 1);
    }

    public function test_user_can_delete_their_post()
    {
        $this->test_user_can_send_post();
        $this->deleteJson(route('profile-posts-deletePost', ['post' => 1]));
        $this->assertDatabaseCount('posts', 0);
    }

    public function test_user_can_update_their_post()
    {
        $this->test_user_can_send_post();
        $this->patchJson(route('profile-posts-updatePost', ['post' => 1]), ['title' => '12345', 'text' => 'hh1234567891010',])
            ->assertSee('updated successfully');
        $this->assertDatabaseHas('posts', ['title' => '12345', 'text' => 'hh1234567891010',]);
    }


    public function test_show_all_posts_for_everyone()
    {
        Post::factory()->count(2)->create();
        Post::factory()->state(['user_id' => '2', 'status' => 'accepted'])->create();
        $this->assertDatabaseCount('posts', 3);
        $this->getJson(route('posts-all'))
            ->assertJson(['data' => [['title' => 'mmmm']]])
            ->assertSee('accepted')
            ->assertDontSee('pending');
    }

    public function test_show_all_categories_for_everyone()
    {
        Category::factory()->create();
        $this->getJson(route('categories-all'))->assertSee('C1');
    }

    public function test_show_all_posts_in_one_category()
    {
        Category::factory()->create();
        Post::factory()->count(2)->create();
        Post::factory()->state(['user_id' => '2', 'status' => 'accepted'])->create();
        Post::factory()->state(['status' => 'accepted', 'category_id' => 2])->create();
        $this->assertDatabaseCount('posts', 4);

        $this->getJson(route('posts-all-oneCategory', 1))->assertJsonFragment(['id' => 3]);
    }

    public function test_show_one_complete_post()
    {
        Post::factory()->count(2)->create();
        Post::factory()->state(['user_id' => '2'])->create();
        $this->assertDatabaseCount('posts', 3);

        $this->getJson(route('posts-onePost', ['post' => '1']))->assertJson([
            'id' => '1',
            'text' => 's mmmmmm',
        ]);
    }

    public function test_everyone_can_like_post()
    {
        Post::factory()->create();
        $this->postJson(route('post-like', 1));
        $this->assertDatabaseCount('likes', 1);
    }

    public function test_everyone_can_like_one_post_just_for_the_first_time()
    {
        Post::factory()->create();
        $this->postJson(route('post-like', 1));
        $this->postJson(route('post-like', 1))->assertSee('you already liked this post');
        $this->assertDatabaseCount('likes', 1);

    }

    public function test_everyone_can_see_tags()
    {
//      Post::factory()->has(Tag::factory()->count(2))->create();
        Tag::factory()->count(2)->create();
        $this->getJson(route('tags-all'))
            ->assertJsonStructure([
                [
                    'id',
                    'tag'
                ]
            ]);
    }

    public function test_everyone_can_search_posts_by_tag()
    {
        Post::factory()->state(['status' => 'accepted'])->count(3)->has(Tag::factory()->count(2))->create();
        $this->get(route('posts-all-oneTag', 1))->assertSee(['T1', 'mmmm']);
    }


    public function test_everyone_can_see_comments_of_one_post()
    {
        $post = Post::factory()->has(Comment::factory()->count(3))->create();
        $this->getJson(route('comments-onePost', $post->id))->assertStatus(200)
            ->assertJsonStructure([
                [
                    'id',
                    'name',
                    'title'
                ]
            ]);
    }

    public function test_users_can_send_comment()
    {
        User::factory()->create();
        $post = Post::factory()->create();
        $response = $this->postJson(route('login'), [
            'username' => 'sina',
            'password' => 'password'
        ]);
        $this->postJson(route('comments-addComment', $post->id), ['comment' => 'my comment'], ['authorization' => 'Bearer ' . $response['token']])
            ->assertSee('Comment created');
        $this->assertDatabaseCount('comments', 1);
    }

    public function test_nobody_can_send_comment_without_logging_in()
    {
        $post = Post::factory()->create();
        $this->postJson(route('comments-addComment', $post->id), ['comment' => 'my comment'])->assertSee('Unauthenticated');
    }
}






