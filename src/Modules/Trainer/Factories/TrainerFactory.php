<?php

namespace Modules\Trainer\Factories;

use Modules\Gym\Models\Gym;
use Shared\Database\DatabaseService;

class TrainerFactory
{
    public const TABLE_NAME = 'Trainer';

    private DatabaseService $databaseService;

    public function __construct(DatabaseService $databaseService)
    {
        $this->databaseService = $databaseService;
    }

    public function getById(int $userId)
    {
        return $this->databaseService->getTable(self::TABLE_NAME)->where('id', $userId)->first();
    }

    public function getByEmail(string $email)
    {
        return $this->databaseService->getTable(self::TABLE_NAME)->where('email', $email)->first();
    }

    public function create(string $email, Gym $gym)
    {
        return $this->databaseService->getTable(self::TABLE_NAME)->insert(
            [
                'email' => $email,
                'gym_id' => $gym->id,
            ]);
    }


}