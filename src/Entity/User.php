<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;

class User
{
    private int $id;

    public string $password;

    private Collection $roles;

    public string $cnp = '';

    public string $email = '';

    public string $firstName = '';

    public string $lastName = '';

    private Collection $programmes;

    public function getId(): int
    {
        return $this->id;
    }

    public function getRoles(): Collection
    {
        return $this->roles;
    }

    public function setRole(Collection $roles): self
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
}

