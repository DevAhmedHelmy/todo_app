<?php

namespace App\Entities;

use App\Models\User;

class UserEntity
{
    private int $id;
    private string $name;
    private string $email;
    private string $password;


    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public static function fromModel(User $user): self
    {
        $entity = new self();
        $entity->setId($user->id);
        $entity->setName($user->name);
        $entity->setEmail($user->email);
        return $entity;
    }


}
