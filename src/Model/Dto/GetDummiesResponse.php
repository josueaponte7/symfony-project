<?php

namespace App\Model\Dto;

class GetDummiesResponse
{
    public function __construct(
        readonly public int $id,
        readonly public string $name,
        readonly public string $email
    ) {
    }
}