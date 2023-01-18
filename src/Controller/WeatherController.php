<?php
declare(strict_types=1);

namespace App\Controller;

use App\Api\WeatherInterface;
use App\Entity\Locations;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class WeatherController extends AbstractController
{
    private WeatherInterface $weatherApi;

    public function __construct(
        WeatherInterface $weatherApi
    ) {
        $this->weatherApi = $weatherApi;
    }

    /**
     * @Route("/api/weather", name="weather", methods={"GET"})
     *
     * @return Response
     */
    public function forecast(): Response
    {
        $apiResponse = [];
        $locations = new Locations();
        $cities = $locations->getCities();

        try {
            foreach ($cities as $city) {
                $apiResponse[$city]['current'] = $this->weatherApi->getCurrentForGivenCoordinates($city);
                $apiResponse[$city]['week'] = $this->weatherApi->getWeekForGivenCoordinates($city);
            }
        } catch (TransportExceptionInterface $e) {
            $this->addFlash('warning', 'No result');
        }

        return $this->json($apiResponse);
    }
}