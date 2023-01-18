<?php
declare(strict_types=1);

namespace App\Api;

use stdClass;
use Symfony\Component\HttpFoundation\Response;

interface WeatherInterface
{
    /**
     * @param string $location
     * @return stdClass
     */
    public function getCurrentForGivenCoordinates(string $location): stdClass;

    /**
     * @param string $location
     * @return stdClass
     */
    public function getWeekForGivenCoordinates(string $location): stdClass;
}