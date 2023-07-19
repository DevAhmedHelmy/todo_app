<?php

namespace App\DTO;

use Carbon\Carbon;
use App\Entities\TodoEntity;

class TodoDto
{
    private $id;
    private $title;
    private $description;
    private $status;
    private $priority;
    private $due_date;
    private $created_at;
    private $updated_at;

    public static function fromRequest(array $request): self
    {
        $dto = new self();
        $dto->setId($request['id'] ?? 0);
        $dto->setTitle($request['title']);
        $dto->setDescription($request['description']);
        $dto->setStatus($request['status'] ?? 'incomplete');
        $dto->setPriority($request['priority']);
        $dto->setDueDate($request['due_date'] ?? Carbon::now());
        $dto->setCreatedAt($request['created_at'] ?? Carbon::now());
        $dto->setUpdatedAt($request['updated_at'] ?? Carbon::now());
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


    public static function fromEntity(TodoEntity $entity): TodoDto
    {
        $dto = new self();
        $dto->setId($entity->getId());
        $dto->setTitle($entity->getTitle());
        $dto->setStatus($entity->getStatus());
        $dto->setPriority($entity->getPriority());
        $dto->setDueDate($entity->getDueDate());
        $dto->setDescription($entity->getDescription());
        $dto->setCreatedAt($entity->getCreatedAt());
        $dto->setUpdatedAt($entity->getUpdatedAt());
        return $dto;
    }
}
