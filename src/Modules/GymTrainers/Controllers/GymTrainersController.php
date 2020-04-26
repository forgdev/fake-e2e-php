<?php

namespace Modules\GymTrainers\Controllers;

use DateTime;
use Exception;
use Modules\GymTrainers\Services\GymTrainersService;
use Psr\Http\Message\ServerRequestInterface;

class GymTrainersController
{

    private GymTrainersService $gymTrainersService;

    public function __construct(GymTrainersService $gymTrainersService)
    {
        $this->gymTrainersService = $gymTrainersService;
    }

    public function assign(ServerRequestInterface $request): array
    {
        $body = $request->getBody();

        if (!isset($body['gymId'], $body['trainerId'], $body['from'], $body['to'])) {
            return ['error' => 'Invalid request'];
        }

        try {
            $room = $this->gymTrainersService->assign($body['gymId'],
                $body['trainerId'],
                new DateTime($body['from']),
                new DateTime($body['to']));
        } catch (Exception $exception) {
            return ['error' => $exception->getMessage()];
        }

        return [
            'room' => $room,
        ];
    }

}