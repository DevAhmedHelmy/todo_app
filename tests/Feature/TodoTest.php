<?php

namespace Tests\Feature;

use App\Models\Todo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class TodoTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('passport:install');
        $this->user = User::factory()->create(['email' => 'test@example.com', 'password' => 'password']);
    }
    // public function test_unautnhenticated_user_cannot_access_todo_list()
    // {
    //     $response = $this->getJson('/api/todo');
    //     $response->assertStatus(401);
    // }
    public function test_todo_list_by_login_user()
    {
        $todo = Todo::factory()->create(['user_id' => $this->user->id]);
        $response = $this->actingAs($this->user, 'api')->getJson('/api/todo');
        $response->assertStatus(200)
            ->assertJsonCount(1, 'data.data')
            ->assertJsonFragment(['id' => $todo->id]);
    }
    public function test_todo_list_paginated_data_correctly()
    {
        Todo::factory(20)->create(['user_id' => $this->user->id]);
        $response = $this->actingAs($this->user, 'api')->getJson('/api/todo');
        $response->assertStatus(200)
            ->assertJsonCount(10, 'data.data')
            ->assertJsonPath('data.last_page', 2)
            ->assertJsonStructure([
                'data' => [
                    'data' => [
                        '*' => [
                            'id',
                            'title',
                            'description',
                            'due_date',
                            'priority',
                            'status',
                        ],
                    ],
                ],

            ]);
    }

    public function test_unautnhenticated_user_cannot_add_new_todo_list()
    {
        $response = $this->postJson('/api/todo');
        $response->assertStatus(401);
    }

    public function test_can_add_new_todo()
    {
        $response = $this->actingAs($this->user, 'api')->postJson('/api/todo', [
            'title' => 'test',
            'description' => 'test',
            'due_date' => '2022-01-01',
            'priority' => 'low',
            'status' => 'incomplete',

        ]);
        $response->assertStatus(201);
        $this->assertDatabaseHas('todos', [
            'title' => 'test',
        ]);
    }

    public function test_user_can_update_todo_list()
    {
        $todo = Todo::factory()->create(['user_id' => $this->user->id]);
        $response = $this->actingAs($this->user, 'api')->putJson('/api/todo/' . $todo->id, [
            'title' => 'update test',
            'description' => $todo->description,
            'due_date' => $todo->due_date,
            'priority' => $todo->priority,
            'status' => $todo->status,
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseHas('todos', [
            'title' => 'update test',
        ]);
    }

    public function test_user_can_update_status_of_todo()
    {
        $todo = Todo::factory()->create(['user_id' => $this->user->id, 'status' => 'incomplete']);
        $response = $this->actingAs($this->user, 'api')->putJson('/api/todo/' . $todo->id . '/update/status', [
            'status' => 'complete',
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseHas('todos', [
            'status' => 'complete',
        ]);
    }
    public function test_user_can_update_priority_of_todo()
    {
        $todo = Todo::factory()->create(['user_id' => $this->user->id, 'priority' => 'low']);
        $response = $this->actingAs($this->user, 'api')->putJson('/api/todo/' . $todo->id . '/update/priority', [
            'priority' => 'medium',
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseHas('todos', [
            'priority' => 'medium',
        ]);
    }

    public function test_user_can_show_todo()
    {
        $todo = Todo::factory()->create(['user_id' => $this->user->id]);
        $response = $this->actingAs($this->user, 'api')->getJson('/api/todo/' . $todo->id);
        $response->assertStatus(200);
        $response->assertJsonFragment(['id' => $todo->id]);
        $this->assertDatabaseHas('todos', [
            'title' => $todo->title,
        ]);
    }

    public function test_user_can_delete_todo()
    {
        $todo = Todo::factory()->create(['user_id' => $this->user->id]);
        $response = $this->actingAs($this->user, 'api')->deleteJson('/api/todo/' . $todo->id);
        $response->assertStatus(200);
        $this->assertDatabaseMissing('todos', [
            'id' => $todo->id,
        ]);
    }

    /**
     * @dataProvider governorateDate
     */
    public function test_request_validation($governorateDate)
    {
        $response = $this->actingAs($this->user, 'api')->postJson('/api/todo', $governorateDate);
        $response->assertStatus(422);
    }


    public static function governorateDate(): array
    {

        return [
            'Title Is Required' => [
                [
                    'description' => 'test description',
                    'due_date' => now(),
                    'priority' => 'low'
                ]
            ],
            'Description Is Required' => [
                [
                    'title' => 'test title',
                    'due_date' => now(),
                    'priority' => 'low'
                ]
            ],
            'Due Date Is Required' => [
                [
                    'title' => 'tets title',
                    'description' => 'test description',
                    'priority' => 'low'
                ]
            ],
            'Priority Is Required' => [
                [
                    'title' => 'tets title',
                    'description' => 'test description',
                    'due_date' => now()
                ]
            ],

        ];
    }
}
