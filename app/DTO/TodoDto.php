<?php

namespace App\DTO;

use DateTime;
use Carbon\Carbon;
use App\DTO\UserDTO;
use jsonSerializable;
use App\Entities\TodoEntity;
use App\Models\User;

class TodoDto implements jsonSerializable
{
    private int $id;
    private string $title;
    private string $description;
    private string $status;
    private string $priority;
    private ?DateTime $due_date;
    private UserDTO $user;

    public static function fromRequest(array $request): self
    {
        $dto = new self();
        $dto->setId($request['id'] ?? 0);
        $dto->setTitle($request['title']);
        $dto->setDescription($request['description']);
        $dto->setStatus($request['status'] ?? 'incomplete');
        $dto->setPriority($request['priority']);
        $dto->setDueDate(Carbon::parse($request['due_date']) ?? Carbon::parse(Carbon::now()->addDays(3)));
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

    public function setUser($user): void
    {
        $this->user = UserDTO::fromEntity($user);
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
            'ID'            => $this->getId(),
            'title'         => $this->getTitle(),
            'Description'   => $this->getDescription(),
            'Status'        => $this->getStatus(),
            'Priority'      => $this->getPriority(),
            'Due Date'      => $this->getDueDate(),
            'User'          => $this->getUser(),
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
