<?php

namespace App\Entity;

use App\Controller\Dto\UserDto;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator as MyAssert;

/**
 * @ORM\Entity()
 */
class User
{
    public const ROLE_USER = 'ROLE_USER';
    public const ROLE_ADMIN = 'ROLE_ADMIN';
    public const ROLES = ['ROLE_USER', 'ROLE_ADMIN'];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id = 0;

    /**
     * @ORM\Column(type="string", length=255, nullable="false")
     * @Assert\NotBlank()
     * @Assert\Regex("/^[A-Z][a-z]+$/")
     * @Groups({api:programme:all})
     */
    public string $firstName = '';

    /**
     * @ORM\Column(type="string", length=255, nullable="false")
     * @Assert\Regex("/^[A-Z][a-z]+$/")
     * @Assert\NotBlank()
     * @Groups({api:programme:all})
     */
    public string $lastName = '';

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email
     * @Assert\NotBlank()
     */
    public string $email = '';

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @MyAssert\Password
     */
    public string $password = '';

    /**
     * @Assert\Regex ("/^[0-9a-zA-Z\.\_]{8,}$/")
     */
    public ?string $plainPassword = '';

    /**
     * @ORM\Column(type="string", length=13, options={"fixed" = true})
     * @MyAssert\Cnp
     * @Assert\NotBlank()
     * @Assert\Regex("/^([1-8])([0-9]{2})(0[1-9]|1[0-2])(0[1-9]|[1-2][0-9]|3[0-1])(0[0-9]|[1-3]\d|4[0-8])(\d{3})([0-9])$/")
     */
    public string $cnp = '';

    /**
     * @ORM\Column(type="json")
     * @Assert\Choice(choices=User::ROLES, multiple=true)
     */
    private array $roles = [];

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
        $roles = $this->roles;

        $roles[] = self::ROLE_USER;

        return array_values(array_unique($roles));
    }

    public function setRoles(array $roles): self
    {
        $this->roles = array_values(array_unique($roles));

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

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

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

    public function removeProgramme(Programme $programme): self
    {
        if (!$this->programmes->contains($programme)) {
            return $this;
        }

        $this->programmes->removeElement($programme);
        $programme->removeCustomer($this);

        return $this;
    }

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
        $user->firstName = $userDto->firstName;
        $user->lastName = $userDto->lastName;
        $user->email = $userDto->email;
        $user->cnp = $userDto->cnp;
        $user->password = $userDto->password;

        return $user;
    }
}
