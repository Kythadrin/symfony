<?php
declare(strict_types=1);

namespace App\Api;

use App\Entity\Location;
use GuzzleHttp\Client;
use Symfony\Component\Serializer\SerializerInterface;

class WeatherApi implements WeatherApiInterface
{
    private Client $weatherClient;
    private SerializerInterface $serializer;
    private string $apiKey;

    public function __construct(
        Client $weatherClient,
        SerializerInterface $serializer,
        string $apiKey
    ) {
        $this->weatherClient = $weatherClient;
        $this->serializer = $serializer;
        $this->apiKey = $apiKey;
    }

    /**
     * @param Location $location
     * @return ApiResponse
     */
    public function getCurrentForGivenCoordinates(Location $location): ApiResponse
    {
        $baseUri = '/data/3.0/weather?lang=en&units=metric&APPID=' . $this->apiKey;
        $params = sprintf('&lat=%s&lon=%s', $location->getLatitude(), $location->getLongitude());
        $response = $this->weatherClient->get($baseUri . $params);

        return $this->serializer->deserialize($response->getBody()->getContents(), ApiResponse::class, 'json');
    }
}