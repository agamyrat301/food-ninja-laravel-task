<?php

namespace App\Filament\Resources\LinkResource\Pages;

use App\Actions\Links\CreateLinkForUser;
use App\Filament\Resources\LinkResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CreateLink extends CreateRecord
{
    protected static string $resource = LinkResource::class;

    /**
     * Route creation through the same action the Blade cabinet uses, so both
     * interfaces create links identically. A non-admin's selection of
     * "user_id" (hidden in the form, but still part of the Livewire state)
     * is never trusted — the owner is always the authenticated user.
     */
    protected function handleRecordCreation(array $data): Model
    {
        $owner = Auth::user()->is_admin
            ? User::findOrFail($data['user_id'])
            : Auth::user();

        return app(CreateLinkForUser::class)($owner, $data['original_url'], $data['code'] ?? null);
    }
}
