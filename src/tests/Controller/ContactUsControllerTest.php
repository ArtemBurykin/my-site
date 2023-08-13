<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Csrf\TokenStorage\SessionTokenStorage;

class ContactUsControllerTest extends WebTestCase
{
    public function dataProviderIncorrectFormData()
    {
        return [
            'no token' => [[
                'email' => 'test@gmail.com',
                'theme' => 'a theme',
                'message' => 'test message',
            ]],
            'no email' => [[
                '_token' => 'token',
                'theme' => 'a theme',
                'message' => 'test message',
            ]],
            'no theme' => [[
                '_token' => 'token',
                'email' => 'test@gmail.com',
                'message' => 'test message',
            ]],
            'no message' => [[
                '_token' => 'token',
                'theme' => 'a theme',
                'email' => 'test@gmail.com',
            ]],
        ];
    }

    /**
     * @dataProvider dataProviderIncorrectFormData
     */
    public function testFailFormDataIncorrect(array $formData)
    {
        $client = static::createClient();
        $client->request(Request::METHOD_POST, $this->getUrl(), $formData);

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);

        $content = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('The form is not filled correctly', $content['message']);
    }

    public function testFailIncorrectCsrfToken()
    {
        $client = static::createClient();

        $this->setCsrfToken('a_token');

        $client->request(
            Request::METHOD_POST,
            $this->getUrl(),
            ['_token' => 'other', 'email' => 'test@gmail.com', 'theme' => 'topic', 'message' => 'test']
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);

        $content = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('The csrf token is incorrect', $content['message']);
    }

    public function testSuccessful()
    {
        $client = static::createClient();

        $token = 'a_token';
        $this->setCsrfToken($token);

        $address = 'test@gmail.com';
        $theme = 'a topic';
        $message = 'test message';
        $client->request(
            Request::METHOD_POST,
            $this->getUrl(),
            ['_token' => $token, 'email' => $address, 'theme' => $theme, 'message' => $message]
        );
        $this->assertResponseIsSuccessful();

        $this->assertQueuedEmailCount(1);

        $email = $this->getMailerMessage();
        // services.yaml::contactEmail
        $this->assertEmailAddressContains($email, 'to', 'test@example.com');
        $this->assertEmailHtmlBodyContains($email, "Email: $address");
        $this->assertEmailHtmlBodyContains($email, "Theme: $theme");
        $this->assertEmailHtmlBodyContains($email, "Message: $message");
    }

    private function getUrl(): string
    {
        return '/api/contact-us';
    }

    private function setCsrfToken(string $token): void
    {
        static::getContainer()->get('event_dispatcher')->addListener(
            KernelEvents::REQUEST,
            function (RequestEvent $event) use ($token) {
                /** @var Session $session */
                $session = static::getContainer()->get('session.factory')->createSession();
                $session->set(SessionTokenStorage::SESSION_NAMESPACE.'/contact-form', $token);
                $event->getRequest()->setSession($session);
            }
        );
    }
}
