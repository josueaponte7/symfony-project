<?php

namespace App\Service\Utils;

use App\Model\Dto\GetCitiesResponse;
use Exception;

class GetCities
{
    public function __construct(private HttpClientInterface $httpClient)
    {
    }

    public function __invoke(string $name): GetCitiesResponse
    {
        $response = $this->httpClient->request('GET', sprintf('https://countriesnow.space/api/v0.1/countries/%s', $name), []);
        $statusCode = $response->getStatusCode();

        if (200 !== $statusCode) {
            throw new Exception('Error obteniendo la ciudad');
        }
        $content = $response->getContent();
        $cities = json_decode($content, true, 512, JSON_THROW_ON_ERROR)['data'];
        $keyCity = array_rand($cities, 1);

        $city = (object) ($cities)[$keyCity];

        return new GetCitiesResponse($city->name, $city->capital);
    }
}
