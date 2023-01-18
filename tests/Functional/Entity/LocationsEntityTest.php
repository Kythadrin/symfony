<?php
declare(strict_types=1);

namespace App\Tests\Functional\Entity;

use App\Entity\Locations;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class LocationsEntityTest extends WebTestCase
{
    public function testGetPage(): void
    {
        $locations = new Locations();

        $locations->setCities(['Jurmala', 'Moscow']);
        $locations->setCities('Tallin');

        $this->assertEquals(['Riga', 'New York', 'Jurmala', 'Moscow', 'Tallin'], $locations->getCities());
    }
}
