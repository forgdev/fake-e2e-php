<?php

namespace Modules\Gym\Factories;

use Pixie\Exception;
use Shared\Database\DatabaseService;
use stdClass;

class GymFactory
{

    public const TABLE_NAME = 'Gym';

    private DatabaseService $databaseService;

    public function __construct(DatabaseService $databaseService)
    {
        $this->databaseService = $databaseService;
    }

    /**
     * @param int $gymId
     * @return stdClass|null
     * @throws Exception
     */
    public function getGym(int $gymId)
    {
        return $this->databaseService->getTable(self::TABLE_NAME)->where('id', $gymId)->first();
    }

    public function getGymByEndpoint(string $endpoint)
    {
        return $this->databaseService->getTable(self::TABLE_NAME)->where('endpoint', $endpoint)->first();
    }

    public function createGym(string $endpoint)
    {
        return $this->databaseService->getTable(self::TABLE_NAME)->insert(['endpoint' => $endpoint]);
    }


}