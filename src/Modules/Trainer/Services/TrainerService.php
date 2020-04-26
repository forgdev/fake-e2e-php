<?php

namespace Modules\Trainer\Services;

use Exception;
use Modules\Gym\Services\GymService;
use Modules\Trainer\Factories\TrainerFactory;
use Modules\Trainer\Models\Trainer;

class TrainerService
{

    private TrainerFactory $trainerFactory;

    private GymService $gymService;

    public function __construct(TrainerFactory $trainerFactory, GymService $gymService)
    {
        $this->trainerFactory = $trainerFactory;
        $this->gymService = $gymService;
    }

    /**
     * @param int $trainerId
     * @return Trainer
     * @throws Exception
     */
    public function getTrainer(int $trainerId): Trainer
    {
        $trainer = $this->trainerFactory->getById($trainerId);

        if (!$trainer) {
            throw new Exception('Trainer not found');
        }

        return new Trainer($trainer);
    }

    /**
     * @param string $email
     * @param int $gymId
     * @return array|string
     * @throws Exception
     */
    public function createTrainer(string $email, int $gymId)
    {
        $trainer = $this->trainerFactory->getByEmail($email);

        if ($trainer) {
            throw new Exception('Trainer already exists');
        }

        $gym = $this->gymService->getGym($gymId);

        return $this->trainerFactory->create($email, $gym);
    }

}