<?php

namespace Tests\Feature\Filament;

use App\Filament\Resources\LinkResource\Pages\CreateLink;
use App\Filament\Resources\LinkResource\Pages\ListLinks;
use App\Models\Link;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class LinkResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_regular_user_only_sees_their_own_links(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        $otherUser = User::factory()->create(['is_admin' => false]);

        $ownLink = Link::factory()->create(['user_id' => $user->id]);
        Link::factory()->create(['user_id' => $otherUser->id]);

        $this->actingAs($user);

        Livewire::test(ListLinks::class)
            ->assertCanSeeTableRecords([$ownLink])
            ->assertCountTableRecords(1);
    }

    public function test_admin_sees_every_users_links(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $linkA = Link::factory()->create();
        $linkB = Link::factory()->create();

        $this->actingAs($admin);

        Livewire::test(ListLinks::class)
            ->assertCanSeeTableRecords([$linkA, $linkB])
            ->assertCountTableRecords(2);
    }

    public function test_regular_user_cannot_assign_a_link_to_another_user_via_the_form(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        $otherUser = User::factory()->create(['is_admin' => false]);

        $this->actingAs($user);

        Livewire::test(CreateLink::class)
            ->set('data.user_id', $otherUser->id)
            ->set('data.original_url', 'https://tampered-owner.example.com')
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('links', [
            'original_url' => 'https://tampered-owner.example.com',
            'user_id' => $user->id,
        ]);
    }

    public function test_admin_can_assign_a_link_to_any_user(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $targetUser = User::factory()->create(['is_admin' => false]);

        $this->actingAs($admin);

        Livewire::test(CreateLink::class)
            ->set('data.user_id', $targetUser->id)
            ->set('data.original_url', 'https://admin-assigned.example.com')
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('links', [
            'original_url' => 'https://admin-assigned.example.com',
            'user_id' => $targetUser->id,
        ]);
    }
}
