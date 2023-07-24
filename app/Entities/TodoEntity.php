<?php

namespace App\Entities;

use App\Entities\UserEntity;
use DateTime;
use App\Models\Todo;

class TodoEntity
{
    private ?int $id;
    private string $title;
    private string $description;
    private string $status;
    private string $priority;
    private ?DateTime $due_date;
    private ?UserEntity $user;
    private ?DateTime $created_at;
    private ?DateTime $updated_at;


    public function __construct(
        ?int $id,
        string $title,
        string $description,
        string $status,
        string $priority,
        ?DateTime $due_date,
        ?UserEntity $user = null,
        ?DateTime $created_at = null,
        ?DateTime $updated_at = null

    ) {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->status = $status;
        $this->priority = $priority;
        $this->due_date = $due_date;
        $this->user = $user;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

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

    public function getUser(): ?UserEntity
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




    public static function fromModel(Todo $todo): self
    {
        return new self(
            $todo->id,
            $todo->title,
            $todo->description,
            $todo->status,
            $todo->priority,
            $todo->due_date,
            UserEntity::fromModel($todo->user),
            $todo->created_at,
            $todo->updated_at
        );

    }

}
