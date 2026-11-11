<?php

namespace Tests\Feature;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test public pages load successfully.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $this->seed(DatabaseSeeder::class);

        $response = $this->get('/');
        $response->assertStatus(200);

        $responseTv = $this->get('/tv');
        $responseTv->assertStatus(200);

        $responseFeed = $this->get('/api/display-feed');
        $responseFeed->assertStatus(200);

        $responseDoctor = $this->get('/doctor');
        $responseDoctor->assertStatus(200);

        $responseMedicines = $this->get('/medicines');
        $responseMedicines->assertStatus(200);
    }

    /**
     * Test ticket generation.
     */
    public function test_can_generate_queue_ticket(): void
    {
        $this->seed(DatabaseSeeder::class);

        $response = $this->post('/ticket/store', [
            'department_id' => 1,
            'full_name' => 'Maria Makiling',
            'phone_number' => '09170001122',
            'is_priority' => 1,
            'priority_type' => 'senior',
            'notes' => 'Headache checkup',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('patients', [
            'full_name' => 'Maria Makiling',
            'is_priority' => 1,
        ]);
    }
}
