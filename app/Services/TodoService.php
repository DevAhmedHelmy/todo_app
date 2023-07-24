<?php

namespace App\Services;

use App\DTO\TodoDTO;
use App\Entities\TodoEntity;
use App\Models\Todo;
use App\Repositories\TodoRepository;
use Illuminate\Auth\AuthManager;

class TodoService
{
    public const DEFAULT_LIMIT = 10;
    private $todoRepository;
    private AuthManager $authService;
    public function __construct(TodoRepository $todoRepository, AuthManager $authService)
    {
        $this->authService = $authService;
        $this->todoRepository = $todoRepository;
    }

    public function getAllByUser($limit = self::DEFAULT_LIMIT, $page = 1)
    {
        $auth = $this->authService->user();
        $total = $this->todoRepository->count($auth->id);

        $todos = $this->todoRepository
            ->getAllByUser($auth->id,$limit, $page)
            ->map(fn (TodoEntity $todo) => TodoDTO::fromEntity($todo))
            ->toArray();
        return new PaginatedService($todos, $total, $limit, $page);
    }
    public function createTodo(TodoDTO $todoDto): TodoDTO
    {
        $entity = new TodoEntity(
            null,
            $todoDto->getTitle(),
            $todoDto->getDescription(),
            $todoDto->getStatus(),
            $todoDto->getPriority(),
            $todoDto->getDueDate(),
        );
        $todo = $this->todoRepository->createTodo($entity, $this->authService->user()->id);
        return TodoDTO::fromEntity($todo);
    }

    public function updateTodo(Todo $todo, TodoDTO $todoDto): TodoDTO
    {
        $entity = new TodoEntity(
            $todo->id,
            $todoDto->getTitle(),
            $todoDto->getDescription(),
            $todoDto->getStatus(),
            $todoDto->getPriority(),
            $todoDto->getDueDate(),
        );

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
