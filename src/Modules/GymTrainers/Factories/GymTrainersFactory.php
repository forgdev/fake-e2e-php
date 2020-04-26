<?php


namespace Modules\GymTrainers\Factories;


use DateTime;
use Modules\Gym\Models\Gym;
use Modules\Trainer\Models\Trainer;
use Shared\Database\DatabaseService;

class GymTrainersFactory
{
    public const TABLE_NAME = 'Gym_Trainers';

    private DatabaseService $databaseService;

    public function __construct(DatabaseService $databaseService)
    {
        $this->databaseService = $databaseService;
    }

    public function get(int $gymId, int $trainerId)
    {
        return $this->databaseService->getTable(self::TABLE_NAME)->where('gym_id', $gymId)->where('trainer_id', $trainerId)->first();
    }

    public function create(int $gymId, int $trainerId, DateTime $from, DateTime $to, int $room)
    {
        return $this->databaseService->getTable(self::TABLE_NAME)->insert([
            'gym_id' => $gymId,
            'trainer_id' => $trainerId,
            'from' => $from->getTimestamp(),
            'to' => $to->getTimestamp(),
            'room' => $room,
        ]);
    }

}