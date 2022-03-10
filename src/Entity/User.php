<?php

namespace App\Entity;

use App\Controller\Dto\UserDto;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator as MyAssert;

/**
 * @ORM\Entity()
 */
class User implements \JsonSerializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id = 0;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    public string $password;

    /**
     * @ORM\Column(type="json")
     */
    private array $roles = [];

    /**
     * @ORM\Column(type="string", length=13)
     * @MyAssert\Cnp
     */
    public string $cnp = '';

    /**
     * @ORM\Column(type="string", length=255)
     */
    public string $email = '';

    /**
     * @ORM\Column(type="string", length=255, nullable="false")
     * @Assert\NotBlank()
     */
    public string $firstName = '';

    /**
     * @ORM\Column(type="string", length=255, nullable="false")
     */
    public string $lastName = '';

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Programme", mappedBy="customers")
     */
    private Collection $programmes;

    public function __construct()
    {
        $this->programmes = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getProgrammes(): Collection
    {
        return $this->programmes;
    }

    public function setProgrammes(Collection $programmes): self
    {
        $this->programmes = $programmes;

        return $this;
    }

    public function addProgramme(Programme $programme): self
    {
        if ($this->programmes->contains($programme)) {
            return $this;
        }

        $this->programmes->add($programme);
        $programme->addCustomer($this);

        return $this;
    }

//    public function removeProgramme(Programme $programme): self
//    {
//        if (!$this->programmes->contains($programme)) {
//            return $this;
//        }
//
//        $this->programmes->removeElement($programme);
//        $programme->removeCustomer($this);
//
//        return $this;
//    }
//
//    public function addRole(string $role): self
//    {
//        if ($this->roles->contains($role)) {
//            return $this;
//        }
//        $this->roles->add($role);
//
//        return $this;
//    }
//
//    public function removeRole(string $role): self
//    {
//        if (!$this->roles->contains($role)) {
//
//            return $this;
//        }
//        $this->roles->removeElement($role);
//
//        return $this;
//    }

    public function jsonSerialize(): array
    {
        return [
            "id" => $this->id,
            "firstName" => $this->firstName,
            "lastName" => $this->lastName,
            "email" => $this->email,
            "cnp" => $this->cnp,
            "roles" => $this->roles,

        ];
    }

    public static function createFromDto(UserDto $userDto): self
    {
        $user = new self();
        $user->cnp = $userDto->cnp;
        $user->password = $userDto->password;
//        $user->confirmPassword = $userDto->confirmPassword;
        $user->email = $userDto->email;
        $user->lastName = $userDto->lastName;
        $user->firstName = $userDto->firstName;

        return $user;
    }
}

