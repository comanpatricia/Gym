<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProgrammeRepository")
 */
class Programme
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups("api:programme:all")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("api:programme:all")
     */
    public string $name = '';

    /**
     * @ORM\Column(type="text")
     * @Groups("api:programme:all")
     */
    public string $description = '';

    /**
     * @ORM\Column (type="datetime")
     * @Groups("api:programme:all")
     */
    private \DateTime $startTime;

    /**
     * @ORM\Column (type="datetime")
     * @Groups("api:programme:all")
     */
    private \DateTime $endTime;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="trainer_id", referencedColumnName="id")
     * @Groups("api:programme:all")
     */
    private ?User $trainer;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Room")
     * @ORM\JoinColumn(name="room_id", referencedColumnName="id")
     * @Groups("api:programme:all")
     */
    private Room $room;

    /**
     * @ORM\ManyToMany(targetEntity="User", inversedBy="programmes")
     * @ORM\JoinTable(name="programmes_customers")
     */
    private Collection $customers;

    /**
     * @ORM\Column(type="boolean")
     * @Groups("api:programme:all")
     */
    public bool $isOnline = false;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     * @Groups("api:programme:all")
     */
    public int $maxParticipants = 0;

    public function __construct()
    {
        $this->customers = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getStartTime(): \DateTime
    {
        return $this->startTime;
    }

    public function setStartTime(\DateTime $startTime): self
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getEndTime(): \DateTime
    {
        return $this->endTime;
    }

    public function setEndTime(\DateTime $endTime): self
    {
        $this->endTime = $endTime;

        return $this;
    }

    public function getTrainer(): ?User
    {
        return $this->trainer;
    }

    public function setTrainer(?User $trainer): self
    {
        $this->trainer = $trainer;

        return $this;
    }

    public function getRoom(): Room
    {
        return $this->room;
    }

    public function setRoom(Room $room): self
    {
        $this->room = $room;

        return $this;
    }

    public function getCustomers(): Collection
    {
        return $this->customers;
    }

    public function setCustomers(Collection $customers): self
    {
        $this->customers = $customers;

        return $this;
    }

    public function addCustomer(User $customer): self
    {
        if ($this->customers->contains($customer)) {
            return $this;
        }

        $this->customers->add($customer);
        $customer->addProgramme($this);

        return $this;
    }

    public function removeCustomer(User $customer): self
    {
        if (!$this->customers->contains($customer)) {
            return $this;
        }

        $this->customers->removeElement($customer);
        $customer->removeProgramme($this);

        return $this;
    }
}
