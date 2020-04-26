<?php

use DI\Container;
use DI\ContainerBuilder;
use Modules\Gym\Controllers\GymController;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Shared\Database\DatabaseService;
use Utils\FixtureLoader;

class GetGymTest extends TestCase
{

    public static function setUpBeforeClass(): void
    {
        $fixtures = include(__DIR__ . '/GymFixtures.php');
        FixtureLoader::preSuite($fixtures, 'Gym');
    }

    private Container $container;

    private array $request;

    public function setUp(): void
    {
        $containerBuilder = new ContainerBuilder();

        $commonDefinitions = include(__DIR__ . '/../../src/diDefinitions.php');

        $containerBuilder->addDefinitions(array_merge(
            Config::getConfig(),
            $commonDefinitions,
            [
                DatabaseService::class => FixtureLoader::$databaseService,
            ]));

        $this->container = $containerBuilder->build();

        FixtureLoader::load(null);

        FixtureLoader::$modelsModified = [];

        FixtureLoader::postLoad();

        $this->request = [
            'id' => 1,
        ];
    }

    public function tearDown(): void
    {
        FixtureLoader::reload();
    }

    public function testAnswersProperly()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getQueryParams')->willReturn($this->request);

        $controller = $this->container->get(GymController::class);

        $response = $controller->getGym($request);

        $this->assertEquals(1, $response['gym']->id);
        $this->assertEquals('https://forgev.com', $response['gym']->endpoint);
    }

    public function testFailsWhenSendingUnknownGymId() {
        $this->request['id'] = 31232131;

        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getQueryParams')->willReturn($this->request);

        $controller = $this->container->get(GymController::class);

        $response = $controller->getGym($request);
        $this->assertArrayHasKey('error', $response);
    }

    public static function tearDownAfterClass(): void
    {
        FixtureLoader::$databaseService->close();
    }

}