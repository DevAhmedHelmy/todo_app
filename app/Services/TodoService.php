<?php

namespace App\Services;

use App\DTO\TodoDto;
use App\Entities\TodoEntity;
use App\Repositories\TodoRepository;

class TodoService
{
    private $todoRepository;
    public function __construct(TodoRepository $todoRepository)
    {
        $this->todoRepository = $todoRepository;
    }

    public function getAll()
    {
        return TodoDto::collection($this->todoRepository->getAll());
    }
    public function createTodo(TodoDto $todoDto): TodoDto
    {
        $entity = new TodoEntity();
        $entity->setTitle($todoDto->getTitle());
        $entity->setDescription($todoDto->getDescription());
        $entity->setStatus($todoDto->getStatus());
        $entity->setPriority($todoDto->getPriority());
        $entity->setDueDate($todoDto->getDueDate());
        $entity->setCreatedAt($todoDto->getCreatedAt());
        $entity->setUpdatedAt($todoDto->getUpdatedAt());
        $todo = $this->todoRepository->createTodo($entity);
        return TodoDto::fromEntity($todo);
    }

    public function updateTodo(int $id, TodoDto $todoDto): TodoDto
    {
        $entity = new TodoEntity();
        $entity->setId($id);
        $entity->setTitle($todoDto->getTitle());
        $entity->setDescription($todoDto->getDescription());
        $entity->setStatus($todoDto->getStatus());
        $entity->setPriority($todoDto->getPriority());
        $entity->setDueDate($todoDto->getDueDate());
        $entity->setCreatedAt($todoDto->getCreatedAt());
        $entity->setUpdatedAt($todoDto->getUpdatedAt());
        $todo = $this->todoRepository->updateTodo($id, $entity);
        return TodoDto::fromEntity($todo);
    }

    public function getById(int $id): TodoDto
    {
        $todo = $this->todoRepository->getById($id);
        return TodoDto::fromEntity($todo);
    }

    public function deleteTodo(int $id): bool
    {
        return $this->todoRepository->deleteTodo($id);
    }

    public function updateStatus(int $id): TodoDto
    {
        $todo = $this->todoRepository->updateStatus($id);
        return TodoDto::fromEntity($todo);
    }

    public function updatePriority(int $id, $priority): TodoDto
    {
        $todo = $this->todoRepository->updatePriority($id, $priority);
        return TodoDto::fromEntity($todo);
    }
}
