<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_homepage_shows_the_bengkel_ar_landing_page(): void
    {
        $response = $this->get('/');

        $response->assertOk()
            ->assertSee('Bengkel AR')
            ->assertSee('Servis lebih tenang.')
            ->assertSee(route('login'))
            ->assertSee(route('register'));
    }
}
