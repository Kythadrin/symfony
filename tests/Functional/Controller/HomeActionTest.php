<?php

namespace App\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class HomeActionTest extends WebTestCase
{
    public function test_get_page(): void
    {
        $client = static::createClient();
        $client->request(Request::METHOD_GET, '/weather');

        $this->assertResponseIsSuccessful();
        $result = $client->getResponse()->getStatusCode();
        $this->assertEquals($result, '200');
    }
}
