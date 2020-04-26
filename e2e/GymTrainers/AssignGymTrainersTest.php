<?php

use DI\Container;
use DI\ContainerBuilder;
use Modules\GymTrainers\Controllers\GymTrainersController;
use Modules\GymTrainers\Factories\GymTrainersFactory;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Shared\Database\DatabaseService;
use Shared\Services\HttpService;
use Utils\FixtureLoader;

class AssignGymTrainersTest extends TestCase
{

    public static function setUpBeforeClass(): void
    {
        $fixtures = include(__DIR__ . '/GymTrainersFixtures.php');
        FixtureLoader::preSuite($fixtures, 'GymTrainer');
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
            'trainerId' => 1,
            'gymId' => 1,
            'from' => (new DateTime())->format('Y-m-d H:i:s'),
            'to' => (new DateTime())->add(new DateInterval('P45M'))->format('Y-m-d H:i:s'),
        ];
    }

    public function tearDown(): void
    {
        FixtureLoader::reload();
    }

    public function testAnswersProperly()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getBody')->willReturn($this->request);

        $httpServiceMock = $this->createStub(HttpService::class);
        $httpServiceMock->method('curlPost')->willReturn(['room' => 1]);
        $this->container->set(HttpService::class, $httpServiceMock);

        $controller = $this->container->get(GymTrainersController::class);

        $response = $controller->assign($request);

        $this->assertIsNumeric($response['room']);
    }

    public function testAnswersProperlyWhenGymIsNotAvailable()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getBody')->willReturn($this->request);

        $httpServiceMock = $this->createStub(HttpService::class);
        $httpServiceMock->method('curlPost')->willReturn(['error' => 'Some error at gym side']);
        $this->container->set(HttpService::class, $httpServiceMock);

        $controller = $this->container->get(GymTrainersController::class);

        $response = $controller->assign($request);

        $this->assertArrayHasKey('error', $response);
    }

    public function testGymTrainersIsCreated()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getBody')->willReturn($this->request);

        $httpServiceMock = $this->createStub(HttpService::class);
        $httpServiceMock->method('curlPost')->willReturn(['room' => 1]);
        $this->container->set(HttpService::class, $httpServiceMock);

        $controller = $this->container->get(GymTrainersController::class);

        $response = $controller->assign($request);

        $gymTrainersFactory = $this->container->get(GymTrainersFactory::class);

        $gymTrainers = $gymTrainersFactory->get(1, 1);

        $this->assertNotNull($gymTrainers);
    }

    public function testGymTrainersIsNotCreatedWhenGymNotAvailable()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getBody')->willReturn($this->request);

        $httpServiceMock = $this->createStub(HttpService::class);
        $httpServiceMock->method('curlPost')->willReturn(['error' => 'Some error at gym side']);
        $this->container->set(HttpService::class, $httpServiceMock);

        $controller = $this->container->get(GymTrainersController::class);

        $response = $controller->assign($request);

        $gymTrainersFactory = $this->container->get(GymTrainersFactory::class);

        $gymTrainers = $gymTrainersFactory->get(1, 1);

        $this->assertNull($gymTrainers);
    }

    public static function tearDownAfterClass(): void
    {
        FixtureLoader::$databaseService->close();
    }
}