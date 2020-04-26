<?php

use Modules\Gym\Factories\GymFactory;
use Modules\Trainer\Factories\TrainerFactory;

$gyms = [
    [
        'id' => 1,
        'endpoint' => 'https://forgev.com',
    ],
    [
        'id' => 2,
        'endpoint' => 'https://repeated-endpoint.com',
    ],
];

$trainers = [
    [
        'id' => 1,
        'email' => 'contact@forgdev.com',
        'gym_id' => $gyms[0]['id'],
    ],
    [
        'id' => 2,
        'email' => 'rmontoya@forgdev.com',
        'gym_id' => $gyms[1]['id'],
    ],
    'depends' => [
        GymFactory::TABLE_NAME,
    ],
];

return [
    GymFactory::TABLE_NAME => $gyms,
    TrainerFactory::TABLE_NAME => $trainers,
];