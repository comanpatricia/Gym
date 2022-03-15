<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Building
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column (type="datetime")
     */
    public \DateTime $startTime;

    /**
     * @ORM\Column (type="datetime")
     */
    public \DateTime $endTime;

    public function getId(): int
    {

        return $this->id;
    }
}
