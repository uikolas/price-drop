<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_show_login_page(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Name');
    }

    public function test_cannot_login(): void
    {
        User::factory()->create([
            'name' => 'username',
            'password' => Hash::make('123'),
        ]);

        $response = $this->post('/login', [
            'name' => 'username',
            'password' => 'invalid',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('name');
        $this->assertGuest();
    }


    public function test_success_login(): void
    {
        $user = User::factory()->create([
            'name' => 'username',
            'password' => Hash::make('123'),
        ]);

        $response = $this->post('/login', [
            'name' => 'username',
            'password' => '123',
        ]);

        $response->assertRedirect('/products');
        $this->assertAuthenticatedAs($user);
    }
}
