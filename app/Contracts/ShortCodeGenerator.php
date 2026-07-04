<?php

namespace App\Contracts;

interface ShortCodeGenerator
{
    /**
     * Generate a single short-code candidate of the given length.
     * Callers are responsible for checking uniqueness.
     */
    public function generate(int $length = 6): string;
}
