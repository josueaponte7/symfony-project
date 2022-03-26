<?php

namespace App\Service\Utils;

use App\Model\Dto\GetDummiesResponse;
use Exception;

class GetDummiesUser
{
    public function __construct(private HttpClientInterface $httpClient)
    {
    }

    public function __invoke(int $userId): GetDummiesResponse
    {
        $response = $this->httpClient->request('GET', sprintf('https://gorest.co.in/public/v2/users/%d', $userId), []);
        $statusCode = $response->getStatusCode();

        if (200 !== $statusCode) {
            throw new Exception('Error recuperando el libro');
        }
        $content = $response->getContent();
        $dummies = json_decode($content, false, 512, JSON_THROW_ON_ERROR);
        return new GetDummiesResponse($dummies->id, $dummies->name, $dummies->email);
    }
}
