<?php

namespace Tests\Feature\Http\Controllers\Admin;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UsersControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_users_returns_all_users_for_ajax_request()
    {
        // Create some users
        $users = User::factory()->count(3)->create();
    
        // Mock the request to be an AJAX request
        $response = $this->json('GET', route('admin.users.getUsers'), [], ['HTTP_X-Requested-With' => 'XMLHttpRequest']);
    
        // Assert the response status
        $response->assertStatus(200);
    
        // Assert the response contains the users
        $response->assertJsonFragment(['id' => $users[0]->id]);
        $response->assertJsonFragment(['id' => $users[1]->id]);
        $response->assertJsonFragment(['id' => $users[2]->id]);
    }
}
