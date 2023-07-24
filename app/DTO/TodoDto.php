<?php

namespace App\DTO;

use DateTime;
use Carbon\Carbon;
use App\DTO\UserDTO;
use jsonSerializable;
use App\Entities\TodoEntity;


class TodoDTO implements jsonSerializable
{
    private ?int $id;
    private string $title;
    private string $description;
    private string $status;
    private string $priority;
    private ?DateTime $due_date;
    private ?UserDTO $user;
    private ?DateTime $created_at;
    private ?DateTime $updated_at;


    public function __construct(
        ?int $id,
        string $title,
        string $description,
        string $status,
        string $priority,
        ?DateTime $due_date,
        ?UserDTO $user = null,
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

    public static function fromRequest(array $request): self
    {
        return new self(
            $request['id'] ?? 0,
            $request['title'],
            $request['description'],
            $request['status'] ?? 'incomplete',
            $request['priority'],
            Carbon::parse($request['due_date']) ?? Carbon::now()->addDays(3),

        );

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





    public static function fromEntity(TodoEntity $entity): TodoDto
    {
        return new self(
            $entity->getId(),
            $entity->getTitle(),
            $entity->getDescription(),
            $entity->getStatus(),
            $entity->getPriority(),
            $entity->getDueDate(),
            UserDTO::fromEntity($entity->getUser()),
            $entity->getCreatedAt(),
            $entity->getUpdatedAt()
        );

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
