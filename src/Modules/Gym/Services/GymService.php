<?php

namespace Modules\Gym\Services;

use Exception;
use Modules\Gym\Factories\GymFactory;
use Modules\Gym\Models\Gym;

class GymService
{

    private GymFactory $gymFactory;

    public function __construct(GymFactory $gymFactory)
    {
        $this->gymFactory = $gymFactory;
    }

    /**
     * @param int $gymId
     * @return Gym
     * @throws \Pixie\Exception
     * @throws Exception
     */
    public function getGym(int $gymId): Gym
    {
        $gym = $this->gymFactory->getGym($gymId);

        if (!$gym) {
            throw new Exception('Gym not found');
        }

        return new Gym($gym);
    }

    /**
     * @param string $endpoint
     * @return array|string
     * @throws Exception
     */
    public function createGym(string $endpoint)
    {
        $gym = $this->gymFactory->getGymByEndpoint($endpoint);

        if ($gym) {
            throw new Exception('There is a gym with such endpoint');
        }

        return $this->gymFactory->createGym($endpoint);
    }


}