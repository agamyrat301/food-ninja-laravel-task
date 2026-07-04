<?php

namespace App\Events;

use App\Models\Link;
use Illuminate\Foundation\Events\Dispatchable;

class LinkVisited
{
    use Dispatchable;

    public function __construct(
        public readonly Link $link,
        public readonly ?string $ipAddress,
        public readonly ?string $userAgent,
    ) {
    }
}
