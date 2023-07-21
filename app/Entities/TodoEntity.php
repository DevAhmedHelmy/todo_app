<?php

namespace App\Entities;

use App\Entities\UserEntity;
use DateTime;
use App\Models\Todo;

class TodoEntity
{
    private int $id;
    private string $title;
    private string $description;
    private string $status;
    private string $priority;
    private UserEntity $user;
    private ?DateTime $due_date;
    private ?DateTime $created_at;
    private ?DateTime $updated_at;


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

    public function getUser()
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

    public function setCreatedAt(DateTime $created_at): void
    {
        $this->created_at = $created_at;
    }
    public function setUpdatedAt(DateTime $updated_at): void
    {
        $this->updated_at = $updated_at;
    }
    public function setUser($user): void
    {
        $this->user = $user;
    }


    public static function fromModel(Todo $todo): self
    {
        $entity = new self();
        $entity->setId($todo->id);
        $entity->setTitle($todo->title);
        $entity->setStatus($todo->status);
        $entity->setPriority($todo->priority);
        $entity->setDueDate($todo->due_date);
        $entity->setDescription($todo->description);
        $entity->setCreatedAt($todo->created_at);
        $entity->setUpdatedAt($todo->updated_at);
        $entity->setUser(UserEntity::fromModel($todo->user));
        return $entity;
    }

    // i want make function take more than 1 entity

    public static function collection($todos): array
    {
        return $todos->map(function ($todo) {
            return self::fromModel($todo);
        })->toArray();
    }
}
