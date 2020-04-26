<?php

use DI\Container;
use DI\ContainerBuilder;
use Modules\Trainer\Controllers\TrainerController;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Shared\Database\DatabaseService;
use Utils\FixtureLoader;

class GetTrainerTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        $fixtures = include(__DIR__ . '/TrainerFixtures.php');
        FixtureLoader::preSuite($fixtures, 'Trainer');
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

        $controller = $this->container->get(TrainerController::class);

        $response = $controller->getTrainer($request);

        $this->assertEquals('contact@forgdev.com', $response['trainer']->email);
        $this->assertEquals(1, $response['trainer']->gym_id);
    }

    public function testFailsWhenSendingUnknownTrainerId()
    {
        $this->request['id'] = 18798798;

        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getQueryParams')->willReturn($this->request);

        $controller = $this->container->get(TrainerController::class);

        $response = $controller->getTrainer($request);

        $this->assertArrayHasKey('error', $response);
    }

    public static function tearDownAfterClass(): void
    {
        FixtureLoader::$databaseService->close();
    }
}