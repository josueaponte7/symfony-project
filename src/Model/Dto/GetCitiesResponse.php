<?php

namespace App\Model\Dto;

class GetCitiesResponse
{
    public function __construct(
        readonly public string $name,
        readonly public string $capital
    ) {
    }
}