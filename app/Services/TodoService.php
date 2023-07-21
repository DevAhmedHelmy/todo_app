<?php

namespace App\Services;

use App\DTO\TodoDTO;
use App\Entities\TodoEntity;
use App\Models\Todo;
use App\Repositories\TodoRepository;
use Illuminate\Auth\AuthManager;

class TodoService
{
    private $todoRepository;
    private AuthManager $authService;
    public function __construct(TodoRepository $todoRepository, AuthManager $authService)
    {
        $this->authService = $authService;
        $this->todoRepository = $todoRepository;
    }

    public function getAllByUser()
    {
        return TodoDTO::collection($this->todoRepository->getAllByUser($this->authService->user()->id));
    }
    public function createTodo(TodoDTO $todoDto): TodoDTO
    {
        $entity = new TodoEntity();
        $entity->setTitle($todoDto->getTitle());
        $entity->setDescription($todoDto->getDescription());
        $entity->setStatus($todoDto->getStatus());
        $entity->setPriority($todoDto->getPriority());
        $entity->setDueDate($todoDto->getDueDate());
        $todo = $this->todoRepository->createTodo($entity, $this->authService->user()->id);
        return TodoDTO::fromEntity($todo);
    }

    public function updateTodo(Todo $todo, TodoDTO $todoDto): TodoDTO
    {
        $entity = new TodoEntity();
        $entity->setId($todo->id);
        $entity->setTitle($todoDto->getTitle());
        $entity->setDescription($todoDto->getDescription());
        $entity->setStatus($todoDto->getStatus());
        $entity->setPriority($todoDto->getPriority());
        $entity->setDueDate($todoDto->getDueDate());
        $updated_todo = $this->todoRepository->updateTodo($todo, $entity, $this->authService->user()->id);
        return TodoDto::fromEntity($updated_todo);
    }

    public function getById(Todo $todo): TodoDTO
    {
        return TodoDTO::fromEntity($this->todoRepository->getById($todo));
    }

    public function deleteTodo(Todo $todo): bool
    {
        return $this->todoRepository->deleteTodo($todo);
    }

    public function updateStatus(int $id): TodoDTO
    {
        $todo = Todo::findOrFail($id);
        return TodoDTO::fromEntity($this->todoRepository->updateStatus($todo));
    }

    public function updatePriority(int $id, $priority): TodoDTO
    {
        $todo = Todo::findOrFail($id);
        return TodoDTO::fromEntity($this->todoRepository->updatePriority($todo, $priority));
    }
}
