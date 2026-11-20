<?php

namespace Tests\Feature;

// use Tests\Traits\RefreshDatabase;

use Tests\BaseTestCase;
class ExampleTest extends BaseTestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('siteman');

        $response->assertStatus(200);
        $response->assertSee('Tampilkan kata sandi');
    }

    public function test_successful_response(): void
    {
        $response = $this->get('siteman/lupa_sandi');

        $response->assertStatus(200);
    }
}
