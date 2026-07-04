<?php

namespace App\Models;

use App\Contracts\ShortCodeGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Link extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'original_url',
        'code',
    ];

    protected static function booted(): void
    {
        static::creating(function (Link $link) {
            if (empty($link->code)) {
                $link->code = static::generateUniqueCode();
            }
        });
    }

    /**
     * Generation strategy is resolved from the container (see
     * App\Contracts\ShortCodeGenerator) so it can be swapped — e.g. for a
     * sequential base62 scheme — without touching this model.
     */
    public static function generateUniqueCode(): string
    {
        $generator = app(ShortCodeGenerator::class);

        do {
            $code = $generator->generate();
        } while (static::where('code', $code)->exists());

        return $code;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function clicks(): HasMany
    {
        return $this->hasMany(Click::class);
    }

    public function getShortUrlAttribute(): string
    {
        return url('/' . $this->code);
    }
}
