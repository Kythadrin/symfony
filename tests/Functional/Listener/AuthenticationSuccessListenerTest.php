<?php

namespace App\Tests\Functional\Listener;

use App\Entity\User;
use App\Listeners\AuthenticationSuccessListener;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AuthenticationSuccessListenerTest extends WebTestCase
{
    /**
     * @throws \Exception
     */
    public function test_authentication_success_listener() : void
    {
        $client = self::createClient();
        $encoder = $client->getContainer()->get(JWTEncoderInterface::class);
        $token = $encoder->encode(['email' => 'test@mail.com', 'password' => 'test']);

        $listener = new AuthenticationSuccessListener('3600');
        $dispatcher = new EventDispatcher();
        $dispatcher->addListener('onAuthenticationSuccess', [$listener, 'onAuthenticationSuccess']);

        $event = new AuthenticationSuccessEvent(
            ['token' => $token],
            $this->createMock(User::class),
            $this->createMock(Response::class),
        );

        $dispatcher->dispatch($event, 'onAuthenticationSuccess');

        self::assertInstanceOf(JsonResponse::class, $event->getResponse());
        self::assertIsString($event->getResponse()->getContent());
        self::assertStringContainsString('message', $event->getResponse()->getContent());
        self::assertStringContainsString('code', $event->getResponse()->getContent());
    }
}