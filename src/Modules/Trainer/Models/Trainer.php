<?php

namespace Modules\Trainer\Models;

class Trainer
{

    public int $id;

    public string $email;

    public int $gym_id;

    public function __construct(array $trainer) {
        $this->id = $trainer['id'];
        $this->email = $trainer['email'];
        $this->gym_id = $trainer['gym_id'];
    }

}