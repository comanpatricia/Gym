<?php

namespace App\Entity;

class Room
{
    private int $id;

    public string $name;

    public string $description;

    public \DateTime $startTime;

    public \DateTime $endTime;

    public function getId(): int
    {

        return $this->id;
    }
}

