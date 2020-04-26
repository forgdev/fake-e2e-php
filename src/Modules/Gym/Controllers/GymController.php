<?php

namespace Modules\Gym\Controllers;

use Exception;
use Modules\Gym\Services\GymService;
use Psr\Http\Message\ServerRequestInterface;

class GymController
{

    private GymService $gymService;

    public function __construct(GymService $gymService)
    {
        $this->gymService = $gymService;
    }


    public function getGym(ServerRequestInterface $request): array
    {
        $params = $request->getQueryParams();

        if (!isset($params['id'])) {
            return [
                'error' => 'Invalid request',
            ];
        }
        try {
            $gym = $this->gymService->getGym($params['id']);
        } catch (Exception $exception) {
            return [
                'error' => $exception->getMessage(),
            ];
        }

        return [
            'gym' => $gym,
        ];
    }

    /**
     * @param ServerRequestInterface $request
     * @return array|string
     * @throws Exception
     */
    public function createGym(ServerRequestInterface $request): array
    {
        $body = $request->getBody();

        if (!isset($body['endpoint'])) {
            return [
                'error' => 'Invalid request',
            ];
        }

        try {
            $id = $this->gymService->createGym($body['endpoint']);
        } catch (Exception $exception) {
            return [
                'error' => $exception->getMessage(),
            ];
        }


        return [
            'gym' => [
                'id' => $id,
            ],
        ];
    }

}