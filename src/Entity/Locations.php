<?php
declare(strict_types=1);

namespace App\Entity;

class Locations
{
    private array $cities = ['Riga', 'New York'];

    /**
     * @param array|string $cities
     */
    public function setCities(array|string $cities): void
    {
        if (!is_array($cities)) {
            $cities = array($cities);
        }

        foreach ($cities as $city) {
            if (!in_array($city, $this->cities)) {
                $this->cities[] = $city;
            }

        }
    }

    /**
     * @return array
     */
    public function getCities(): array
    {
        return $this->cities;
    }
}