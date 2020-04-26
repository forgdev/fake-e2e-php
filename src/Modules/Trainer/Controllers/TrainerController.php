<?php

namespace Modules\Trainer\Controllers;

use Exception;
use Modules\Trainer\Models\Trainer;
use Modules\Trainer\Services\TrainerService;
use Psr\Http\Message\ServerRequestInterface;

class TrainerController
{
    private TrainerService $trainerService;

    public function __construct(TrainerService $trainerService)
    {
        $this->trainerService = $trainerService;
    }

    /**
     * @param ServerRequestInterface $request
     * @return array
     */
    public function getTrainer(ServerRequestInterface $request): array
    {
        $params = $request->getQueryParams();

        if (!isset($params['id'])) {
            return ['error' => 'Invalid request'];
        }

        try {
            $trainer = $this->trainerService->getTrainer($params['id']);
        } catch (Exception $exception) {
            return [
                'error' => $exception->getMessage(),
            ];
        }

        return [
            'trainer' => $trainer,
        ];
    }

    /**
     * @param ServerRequestInterface $request
     * @return array|string
     * @throws Exception
     */
    public function createTrainer(ServerRequestInterface $request): array
    {
        $body = $request->getBody();

        if (!isset($body['email'], $body['gymId'])) {
            return ['error' => 'Invalid request'];
        }

        try {
            $id = $this->trainerService->createTrainer($body['email'], $body['gymId']);
        } catch (Exception $exception) {
            return ['error' => $exception->getMessage()];
        }

        return [
            'trainer' => [
                'id' => $id,
            ],
        ];
    }

}