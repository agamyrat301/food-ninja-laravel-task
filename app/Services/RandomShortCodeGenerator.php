<?php

namespace App\Services;

use App\Contracts\ShortCodeGenerator;
use Illuminate\Support\Str;

class RandomShortCodeGenerator implements ShortCodeGenerator
{
    public function generate(int $length = 6): string
    {
        return Str::random($length);
    }
}
