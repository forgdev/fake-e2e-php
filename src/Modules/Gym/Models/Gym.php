<?php

namespace Modules\Gym\Models;

class Gym
{

    public int $id;

    public string $endpoint;

    public function __construct(array $gym)
    {
        $this->id = $gym['id'];
        $this->endpoint = $gym['endpoint'];
    }

}