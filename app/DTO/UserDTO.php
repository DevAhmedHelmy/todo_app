<?php

namespace App\DTO;

use jsonSerializable;
use App\Entities\UserEntity;

class UserDTO implements jsonSerializable
{
    private int $id;
    private string $name;
    private string $email;

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


    public static function fromEntity(UserEntity $entity): self
    {
        $dto = new self();
        $dto->setId($entity->getId());
        $dto->setName($entity->getName());
        $dto->setEmail($entity->getEmail());
        return $dto;
    }



    public function toArray(): array
    {
        return [
            'id'            => $this->getId(),
            'name'         => $this->getName(),
            'email'   => $this->getEmail(),
        ];
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
