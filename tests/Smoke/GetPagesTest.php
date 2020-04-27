<?php

declare(strict_types=1);

namespace App\Tests\Smoke;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

final class GetPagesTest extends WebTestCase
{
    /**
     * @var KernelBrowser
     */
    private $client;

    /**
     * @var RouterInterface
     */
    private $router;

    protected function setUp(): void
    {
        $this->client = self::createClient();
        $this->router = $this->client->getContainer()->get('router');
    }

    /**
     * @dataProvider getRouteProvider
     */
    public function testGetRoutes(string $route): void
    {
        $url = $this->router->generate($route, [
            '_locale' => 'en',
        ]);
        $this->client->request(Request::METHOD_GET, $url);

        self::assertResponseIsSuccessful();
    }

    public static function getRouteProvider(): array
    {
        return [
            'Index' => ['web_index'],
            'Jobs' => ['web_jobs'],
            'Press' => ['web_press'],
            'Register' => ['web_register'],
            'Imprint' => ['web_imprint'],
            'Privacy' => ['web_privacy'],
        ];
    }
}
