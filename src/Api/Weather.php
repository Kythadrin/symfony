<?php
declare(strict_types=1);

namespace App\Api;

use stdClass;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Serializer\SerializerInterface;

class Weather implements WeatherInterface
{
    private HttpClientInterface $weatherClient;
    private SerializerInterface $serializer;
    private string $endpoint;
    private string $apiKey;

    public function __construct(
        HttpClientInterface $weatherClient,
        SerializerInterface $serializer,
        string $endpoint,
        string $apiKey
    ) {
        $this->weatherClient = $weatherClient;
        $this->serializer = $serializer;
        $this->endpoint = $endpoint;
        $this->apiKey = $apiKey;
    }

    /**
     * @param string $location
     * @return stdClass
     * @throws TransportExceptionInterface
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function getCurrentForGivenCoordinates(string $location): stdClass
    {
        $baseUrl = '/data/2.5/weather?lang=en&units=metric&appid=' . $this->apiKey;

        $coordinates = $this->getLocationCoordinates($location);
        $params = sprintf('&lat=%s&lon=%s', $coordinates->lat, $coordinates->lon);
        $url = $this->endpoint . $baseUrl . $params;
        $response = $this->weatherClient->request(Request::METHOD_GET, $url);

        return json_decode($response->getContent());
    }

    /**
     * @param string $location
     * @return stdClass
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getWeekForGivenCoordinates(string $location): stdClass
    {
        $baseUrl = '/data/2.5/forecast/?lang=en&units=metric&appid=' . $this->apiKey;

        $coordinates = $this->getLocationCoordinates($location);
        $params = sprintf('&lat=%s&lon=%s', $coordinates->lat, $coordinates->lon);
        $url = $this->endpoint . $baseUrl . $params;
        $response = $this->weatherClient->request('GET', $url);

        return json_decode($response->getContent());
    }

    /**
     * @param string $location
     * @return LocationResponse
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    private function getLocationCoordinates(string $location): LocationResponse
    {
        $baseUrl = '/geo/1.0/direct?appid=' . $this->apiKey;
        $params = sprintf('&q=%s', $location);
        $url = $this->endpoint . $baseUrl . $params;

        $response = $this->weatherClient->request('GET', $url);

        return $this->serializer->deserialize(trim($response->getContent(), '[]'), LocationResponse::class, 'json');
    }
}