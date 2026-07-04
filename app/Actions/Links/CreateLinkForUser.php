<?php

namespace App\Actions\Links;

use App\Models\Link;
use App\Models\User;

/**
 * Single-purpose action for creating a link owned by a given user.
 * Shared by the Blade cabinet (LinkController) and the Filament cabinet
 * (LinkResource\Pages\CreateLink) so both interfaces create links the
 * exact same way.
 */
class CreateLinkForUser
{
    /**
     * @param  string|null  $code  Explicit short code (e.g. set by an admin
     *                             in the Filament form); left empty, the
     *                             Link model generates one automatically.
     */
    public function __invoke(User $owner, string $originalUrl, ?string $code = null): Link
    {
        return $owner->links()->create(array_filter([
            'original_url' => $originalUrl,
            'code' => $code,
        ]));
    }
}
