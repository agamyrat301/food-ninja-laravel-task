<?php

namespace Tests\Feature;

use App\Events\LinkVisited;
use App\Models\Link;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class RedirectTest extends TestCase
{
    use RefreshDatabase;

    public function test_visiting_a_short_link_redirects_to_the_original_url(): void
    {
        $link = Link::factory()->create(['original_url' => 'https://example.com/page']);

        $this->get('/' . $link->code)->assertRedirect('https://example.com/page');
    }

    public function test_visiting_a_short_link_dispatches_the_link_visited_event(): void
    {
        Event::fake([LinkVisited::class]);

        $link = Link::factory()->create();

        $this->get('/' . $link->code);

        Event::assertDispatched(
            LinkVisited::class,
            fn (LinkVisited $event) => $event->link->is($link)
        );
    }

    public function test_visiting_a_short_link_records_a_click_with_ip_and_user_agent(): void
    {
        $link = Link::factory()->create();

        $this->withHeaders(['User-Agent' => 'PHPUnit-Agent'])
            ->get('/' . $link->code);

        $this->assertDatabaseHas('clicks', [
            'link_id' => $link->id,
            'user_agent' => 'PHPUnit-Agent',
        ]);
        $this->assertSame(1, $link->clicks()->count());
    }

    public function test_unknown_short_code_returns_a_404(): void
    {
        $this->get('/doesnotexist123')->assertNotFound();
    }
}
