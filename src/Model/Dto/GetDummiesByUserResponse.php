<?php

namespace App\Model\Dto;

class GetDummiesByUserResponse
{
    public function __construct(
        readonly public int $userId,
        readonly public string $title,
        readonly public string $body
    ) {
    }
}