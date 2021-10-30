<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_username_is_required_to_login()
    {
//        ->dump()
        $this->postJson(route('login'))->assertSee("The username field is required");
    }


    public function test_password_is_required_to_login()
    {
        $this->postJson(route('login'))->assertSee("The password field is required");
    }


    public function test_validation_passed_if_username_and_password_insert()
    {
        $this->postJson(route('login'), [
            'username' => "sina",
            'password' => "password",
        ])->assertSee("User not found");
    }


    public function test_login_works()
    {
        User::query()->create([
            'name' => "sina",
            'username' => 'sina',
            'image' => '#',
            'password' => bcrypt('password')
        ]);

        $this->postJson(route('login'), [
            'username' => 'sina',
            'password' => 'password'
        ])
            ->assertStatus(200)
            ->assertSee('token')
            ->assertSee('sina')
            ->assertJson(["user" => [
                "username" => "sina",
                "name" => "sina",
                "image" => "#"
            ]]);

        $this->assertDatabaseHas('users', [
            "username" => "sina",
            "name" => "sina"
        ]);

        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseCount('categories', 0);
    }

    public function test_login_did_not_works_if_the_username_and_password_is_incorrect()
    {
        User::query()->create([
            'name' => "sina",
            'username' => 'sina',
            'image' => '#',
            'password' => bcrypt('password')
        ]);

        $this->postJson(route('login'), [
            'username' => 'sina',
            'password' => 'PASS'
        ])->assertSee('Your username or password is incorrect');
    }

    public function test_signup_works() // todo validation test
    {
        $file = UploadedFile::fake()->image('p.jpg');

        $this->postJson(route('signup'), [
            'name' => 'sina',
            'username' => 'sina',
            'password' => '1234',
            'password_confirmation' => '1234',
            'image' => $file,
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'sina',
            'username' => 'sina',
        ]);
    }

    public function test_signup_validation()
    {
        User::factory()->create(['username' => 'sina']);
        $this->postJson(route('signup'), [
            'username' => 'sina',
            'password' => '1234',
            'password_confirmation' => '1230000000',
            'image' => '%%%',
        ])
        ->assertSee(['The name field is required','The username has already been taken','The password confirmation does not match','The image must be an image']);
    }


    public function test_logout_works()
    {
        /** @var User $user */
        $user = User::query()->create([
            'name' => "sina",
            'username' => 'sina',
            'image' => '#',
            'password' => bcrypt('password')
        ]);

        $response = $this->postJson(route('login'), [
            'username' => 'sina',
            'password' => 'password'
        ]);

        $this->assertDatabaseCount('personal_access_tokens', 1);

        $this->postJson(route('logout'), [], [
            'authorization' => 'Bearer ' . $response['token']
        ]);

        $this->assertDatabaseCount('personal_access_tokens', 0);

    }

    public function test_changepass_works()
    {
        /** @var User $user */
        $user = User::query()->create([
            'name' => "sina",
            'username' => 'sina',
            'image' => '#',
            'password' => bcrypt('password')
        ]);

        $response = $this->postJson(route('login'), [
            'username' => 'sina',
            'password' => 'password'
        ]);
        $this->assertDatabaseCount('personal_access_tokens', 1);

        $this->postJson(route('changePass'),[
            'old_pass' => 'password',  'new_pass' => '1234'
        ],['authorization' => 'Bearer ' . $response['token']])->assertSee('password changed to 1234');
    }

    public function test_change_pass_error()
    {
        $user = User::query()->create([
            'name' => "sina",
            'username' => 'sina',
            'image' => '#',
            'password' => bcrypt('password')
        ]);

        $response = $this->postJson(route('login'), [
            'username' => 'sina',
            'password' => 'password'
        ]);
        $this->assertDatabaseCount('personal_access_tokens', 1);

        $this->postJson(route('changePass'),[
            'old_pass' => 'PASS',  'new_pass' => '1234'
        ],['authorization' => 'Bearer ' . $response['token']])
            ->assertSee('token ERROR');

    }
}




