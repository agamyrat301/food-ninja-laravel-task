<?php

namespace Tests\Feature;

use App\Models\Link;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LinkManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get(route('links.index'))->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_create_a_link(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('links.store'), [
            'original_url' => 'https://example.com/page',
        ]);

        $response->assertRedirect(route('links.index'));
        $this->assertDatabaseHas('links', [
            'user_id' => $user->id,
            'original_url' => 'https://example.com/page',
        ]);

        $link = Link::first();
        $this->assertNotEmpty($link->code);
    }

    public function test_original_url_is_required_and_must_be_a_valid_url(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('links.store'), ['original_url' => 'not-a-url'])
            ->assertSessionHasErrors('original_url');

        $this->assertDatabaseCount('links', 0);
    }

    public function test_user_only_sees_their_own_links_in_the_index(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $ownLink = Link::factory()->create(['user_id' => $user->id]);
        Link::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($user)->get(route('links.index'));

        $response->assertSee($ownLink->short_url, false);
        $response->assertViewHas('links', fn ($links) => $links->count() === 1);
    }

    public function test_user_can_view_stats_for_their_own_link(): void
    {
        $user = User::factory()->create();
        $link = Link::factory()->create(['user_id' => $user->id]);
        $link->clicks()->create(['ip_address' => '10.0.0.1', 'user_agent' => 'PHPUnit']);

        $response = $this->actingAs($user)->get(route('links.show', $link));

        $response->assertOk();
        $response->assertSee('10.0.0.1');
    }

    public function test_user_cannot_view_stats_for_another_users_link(): void
    {
        $user = User::factory()->create();
        $otherUsersLink = Link::factory()->create(['user_id' => User::factory()->create()->id]);

        $this->actingAs($user)
            ->get(route('links.show', $otherUsersLink))
            ->assertForbidden();
    }

    public function test_user_can_delete_their_own_link(): void
    {
        $user = User::factory()->create();
        $link = Link::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->delete(route('links.destroy', $link))
            ->assertRedirect(route('links.index'));

        $this->assertDatabaseMissing('links', ['id' => $link->id]);
    }

    public function test_user_cannot_delete_another_users_link(): void
    {
        $user = User::factory()->create();
        $otherUsersLink = Link::factory()->create(['user_id' => User::factory()->create()->id]);

        $this->actingAs($user)
            ->delete(route('links.destroy', $otherUsersLink))
            ->assertForbidden();

        $this->assertDatabaseHas('links', ['id' => $otherUsersLink->id]);
    }
}
