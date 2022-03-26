<?php

namespace App\Service\Utils;

use Symfony\Contracts\HttpClient\ResponseInterface;

interface HttpClientInterface
{
    /*
     * @param string $method
     * @param string $url
     * @param array $options
     * @return ResponseInterface
     * @throws HttpRequestException
     *
     */
    public function request(string $method, string $url, array $options = []): ResponseInterface;
}