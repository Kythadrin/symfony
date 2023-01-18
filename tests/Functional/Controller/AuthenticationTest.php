<?php
declare(strict_types=1);

namespace App\Tests\Functional\Controller;

use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AuthenticationTest extends WebTestCase
{
    /**
     * @param array $claims
     * @return KernelBrowser
     */
    protected function createAuthenticatedClient(array $claims): KernelBrowser
    {
        $client = self::createClient();
        $encoder = $client->getContainer()->get(JWTEncoderInterface::class);

        $client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $encoder->encode($claims)));

        return $client;
    }

    public function test_get_page(): void
    {
        $client = $this->createAuthenticatedClient(['email' => 'test@mail.com', 'password' => 'test']);
        $client->request('GET', '/weather');

        $this->assertResponseIsSuccessful();
        $result = $client->getResponse()->getStatusCode();
        $this->assertEquals($result, '200');
    }
}