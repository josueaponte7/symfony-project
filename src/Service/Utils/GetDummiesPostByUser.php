<?php

namespace App\Service\Utils;

use App\Model\Dto\GetDummiesByUserResponse;
use Exception;

class GetDummiesPostByUser
{
    public function __construct(private HttpClientInterface $httpClient)
    {
    }

    public function __invoke(string $name): GetDummiesByUserResponse
    {
        $url = sprintf('https://gorest.co.in/public/v2/%s/', $name);
        $response = $this->httpClient->request('GET', $url, []);
        $statusCode = $response->getStatusCode();

        if (200 !== $statusCode) {
            throw new Exception('Error recuperando los post');
        }
        $content = $response->getContent();
        $json = json_decode($content, false, 512, JSON_THROW_ON_ERROR);
        $keyPost = array_rand($json, 1);
        $post = $json[$keyPost];

        return new GetDummiesByUserResponse($post->user_id, $post->title, $post->body);
    }
}
