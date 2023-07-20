<?php

namespace App\Repositories;

use App\Models\Todo;
use App\Entities\TodoEntity;
use Illuminate\Auth\AuthManager;


class TodoRepository
{
    private AuthManager $authService;
    public function __construct(AuthManager $authService)
    {
        $this->authService = $authService;
    }
    public function getAll()
    {
        $todos = $this->authService->user()->todos()->get();
        return TodoEntity::collection($todos);
    }
    public function createTodo(TodoEntity $todoEntity): TodoEntity
    {
        $todo = Todo::create([
            'user_id' => $this->authService->user()->id,
            'title' => $todoEntity->getTitle(),
            'description' => $todoEntity->getDescription(),
            'status' => $todoEntity->getStatus(),
            'priority' => $todoEntity->getPriority(),
            'due_date' => $todoEntity->getDueDate(),
        ]);
        return TodoEntity::fromModel($todo);
    }

    public function updateTodo(int $id, TodoEntity $todoEntity): TodoEntity
    {
        $todo = Todo::findOrFail($id);
        $todo->update([
            'user_id' => $this->authService->user()->id,
            'title' => $todoEntity->getTitle(),
            'description' => $todoEntity->getDescription(),
            'status' => $todoEntity->getStatus(),
            'priority' => $todoEntity->getPriority(),
            'due_date' => $todoEntity->getDueDate(),
        ]);
        return TodoEntity::fromModel($todo);
    }

    public function getById(int $id): TodoEntity
    {
        $todo = $this->authService->todos()->findOrFail($id);
        return TodoEntity::fromModel($todo);
    }

    public function deleteTodo(int $id): bool
    {
        return $this->authService->todos()->findOrFail($id)->delete();
    }

    public function updateStatus(int $id): TodoEntity
    {
        $todo =  $this->authService->todos()->findOrFail($id);
        $status = $todo->status == 'complete' ? 'incomplete' : 'complete';
        $todo->update(['status' => $status]);
        return TodoEntity::fromModel($todo);
    }

    public function updatePriority(int $id, $priority): TodoEntity
    {
        $todo = $this->authService->todos()->findOrFail($id);;
        $todo->update(['priority' => $priority]);
        return TodoEntity::fromModel($todo);
    }
}
