<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoriesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_shows_login_and_register_buttons_when_not_logged_in()
    {
        $this->get('/')
            ->assertDontSeeLivewire('profile')
            ->assertSee('login')
            ->assertSee('register');
    }

    /** @test */
    public function it_shows_profile_button_when_logged_in()
    {
        $this->actingAs($user = User::factory()->create());

        $this->get('/')
            ->assertDontSeeLivewire('login')
            ->assertSee('profile')
            ->assertSee('orders');
    }
}
