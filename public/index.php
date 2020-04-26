<?php

$rootDir = dirname(__DIR__);

require_once "{$rootDir}/src/autoload.php";
require_once "{$rootDir}/src/di.php";

use Modules\Gym\Controllers\GymController;
use Modules\GymTrainers\Controllers\GymTrainersController;
use Modules\Trainer\Controllers\TrainerController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

$app = AppFactory::create();

$trainerController = $container->get(TrainerController::class);
$gymController = $container->get(GymController::class);
$gymTrainersController = $container->get(GymTrainersController::class);

$app->get('/trainer',
    fn(Request $request, Response $response, array $args) => $response->getBody()->write(json_encode($trainerController->getTrainer($request))));
$app->post('/trainer',
    fn(Request $request, Response $response, array $args) => $response->getBody()->write(json_encode($trainerController->createTrainer($request))));

$app->get('/gym',
    fn(Request $request, Response $response, array $args) => $response->getBody()->write(json_encode($gymController->getGym($request))));
$app->post('/gym',
    fn(Request $request, Response $response, array $args) => $response->getBody()->write(json_encode($gymController->createGym($request))));

$app->post('/assign',
    fn(Request $request, Response $response, array $args) => $response->getBody()->write(json_encode($gymTrainersController->assign($request))));

$app->run();
