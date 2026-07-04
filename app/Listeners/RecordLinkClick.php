<?php

namespace App\Listeners;

use App\Events\LinkVisited;

class RecordLinkClick
{
    public function handle(LinkVisited $event): void
    {
        $event->link->clicks()->create([
            'ip_address' => $event->ipAddress,
            'user_agent' => $event->userAgent,
        ]);
    }
}
