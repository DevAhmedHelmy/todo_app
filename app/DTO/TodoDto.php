<?php

namespace App\DTO;

use DateTime;
use Carbon\Carbon;
use App\DTO\UserDTO;
use jsonSerializable;
use App\Entities\TodoEntity;
use App\Entities\UserEntity;


class TodoDTO implements jsonSerializable
{
    private int $id;
    private string $title;
    private string $description;
    private string $status;
    private string $priority;
    private ?DateTime $due_date;
    private UserDTO $user;
    private ?DateTime $created_at;
    private ?DateTime $updated_at;

    public static function fromRequest(array $request): self
    {
        $dto = new self();
        $dto->setId($request['id'] ?? 0);
        $dto->setTitle($request['title']);
        $dto->setDescription($request['description']);
        $dto->setStatus($request['status'] ?? 'incomplete');
        $dto->setPriority($request['priority']);
        $dto->setDueDate(Carbon::parse($request['due_date']) ?? Carbon::now()->addDays(3));
        return $dto;
    }

    // getters
    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getPriority(): string
    {
        return $this->priority;
    }

    public function getDueDate(): ?DateTime
    {
        return $this->due_date;
    }

    public function getUser(): UserDTO
    {
        return $this->user;
    }

    public function getCreatedAt(): ?DateTime
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updated_at;
    }


    // setters
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function setPriority(string $priority): void
    {
        $this->priority = $priority;
    }

    public function setDueDate(DateTime $due_date): void
    {
        $this->due_date = $due_date;
    }

    public function setUser(UserEntity $user): void
    {
        $this->user = UserDTO::fromEntity($user);
    }

    public function setCreatedAt(DateTime $created_at): void
    {
        $this->created_at = $created_at;
    }


    public function setUpdatedAt(DateTime $updated_at): void
    {
        $this->updated_at = $updated_at;
    }



    public static function fromEntity(TodoEntity $entity): TodoDto
    {
        $dto = new self();
        $dto->setId($entity->getId());
        $dto->setTitle($entity->getTitle());
        $dto->setStatus($entity->getStatus());
        $dto->setPriority($entity->getPriority());
        $dto->setDueDate($entity->getDueDate());
        $dto->setDescription($entity->getDescription());
        $dto->setUser($entity->getUser());
        $dto->setCreatedAt($entity->getCreatedAt());
        $dto->setUpdatedAt($entity->getUpdatedAt());
        return $dto;
    }


    public static function collection($entities): array
    {
        return array_map(function ($entity) {
            return self::fromEntity($entity);
        }, $entities);
    }


    public function toArray(): array
    {


        return [
            'id'            => $this->getId(),
            'title'         => $this->getTitle(),
            'description'   => $this->getDescription(),
            'status'        => $this->getStatus(),
            'priority'      => $this->getPriority(),
            'due_date'      => $this->getDueDate(),
            'user'          => $this->getUser(),
            'created_at'    => $this->getCreatedAt(),
            'updated_at'    => $this->getUpdatedAt(),
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
