<?php

namespace Modules\GymTrainers\Services;

use DateTime;
use Exception;
use Modules\Gym\Models\Gym;
use Modules\Gym\Services\GymService;
use Modules\GymTrainers\Factories\GymTrainersFactory;
use Modules\Trainer\Models\Trainer;
use Modules\Trainer\Services\TrainerService;
use Shared\Services\HttpService;

class GymTrainersService
{

    private HttpService $httpService;

    private TrainerService $trainerService;

    private GymService $gymService;

    private GymTrainersFactory $gymTrainersFactory;

    public function __construct(
        HttpService $httpService,
        GymService $gymService,
        TrainerService $trainerService,
        GymTrainersFactory $gymTrainersFactory
    )
    {
        $this->httpService = $httpService;
        $this->gymService = $gymService;
        $this->trainerService = $trainerService;
        $this->gymTrainersFactory = $gymTrainersFactory;
    }

    /**
     * @param int $gymId
     * @param int $trainerId
     * @param DateTime $from
     * @param DateTime $to
     * @return mixed
     * @throws \Pixie\Exception
     * @throws Exception
     */
    public function assign(int $gymId, int $trainerId, DateTime $from, DateTime $to)
    {
        $gym = $this->gymService->getGym($gymId);
        $trainer = $this->trainerService->getTrainer($trainerId);

        $response = $this->assignClassThirdPartyService($gym, $trainer, $from, $to);

        $this->gymTrainersFactory->create($gym->id, $trainer->id, $from, $to, $response['room']);

        return $response['room'];
    }

    /**
     * @param Gym $gym
     * @param Trainer $trainer
     * @param DateTime $from
     * @param DateTime $to
     * @return array
     * @throws Exception
     */
    private function assignClassThirdPartyService(Gym $gym, Trainer $trainer, DateTime $from, DateTime $to): array
    {
        $response = $this->httpService->curlPost("{$gym->endpoint}/assign/class",
            [
                'trainer' => $trainer->email,
                'from' => $from->getTimestamp(),
                'to' => $to->getTimestamp(),
            ]);

        if (isset($response['error'])) {
            throw new Exception('Gym not available');
        }

        return $response;
    }

}