<?php

namespace App\Entities;

use App\Models\Todo;

class TodoEntity
{
    private $id;
    private $title;
    private $description;
    private $status;
    private $priority;
    private $due_date;
    private $created_at;
    private $updated_at;


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

    public function getDueDate(): string
    {
        return $this->due_date;
    }

    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): string
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

    public function setDueDate(string $due_date): void
    {
        $this->due_date = $due_date;
    }

    public function setCreatedAt(string $created_at): void
    {
        $this->created_at = $created_at;
    }

    public function setUpdatedAt(string $updated_at): void
    {
        $this->updated_at = $updated_at;
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
