<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity()
 */
class Room
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({api:programme:all})
     */
    public string $name;

    /**
     * @ORM\Column(type="integer")
     */
    public int $capacity;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Building", mappedBy="room")
     */
    private Building $building;


    public function getId(): int
    {

        return $this->id;
    }

    public function getBuilding(): Building
    {
        return $this->building;
    }

    public function setBuilding(Building $building): self
    {
        $this->building = $building;

        return $this;
    }
}
