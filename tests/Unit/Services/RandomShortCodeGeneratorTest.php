<?php

namespace Tests\Unit\Services;

use App\Services\RandomShortCodeGenerator;
use PHPUnit\Framework\TestCase;

class RandomShortCodeGeneratorTest extends TestCase
{
    public function test_it_generates_a_code_of_the_requested_length(): void
    {
        $generator = new RandomShortCodeGenerator();

        $this->assertSame(6, strlen($generator->generate()));
        $this->assertSame(10, strlen($generator->generate(10)));
    }

    public function test_it_generates_different_codes_on_each_call(): void
    {
        $generator = new RandomShortCodeGenerator();

        $codes = array_map(fn () => $generator->generate(12), range(1, 20));

        $this->assertCount(20, array_unique($codes));
    }
}
