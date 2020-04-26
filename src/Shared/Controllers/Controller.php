<?php

namespace Shared\Controllers;

use Exception;
use Modules\User\Controllers\TrainerController;
use Shared\Services\RequestService;

class Controller
{

    private TrainerController $userController;

    private RequestService $requestService;

    public function __construct(TrainerController $userController, RequestService $requestService)
    {
        $this->userController = $userController;
        $this->requestService = $requestService;
    }

    public function getUsers() {
        try{

        }catch (Exception $exception) {

        }
    }

    public function handleUsers()
    {
        try {
            return $this->userController->handle($this->requestService->getParams(), $this->requestService->getPOSTRequest());
        } catch (Exception $exception) {
            return [
                'error' => $exception->getMessage(),
            ];
        }

    }

}