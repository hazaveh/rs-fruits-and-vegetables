<?php

namespace App\Contract;

interface ParserInterface
{
    /**
     * @return array<int, array<string, string>>
     */
    public function parse(string $data): array;
}
