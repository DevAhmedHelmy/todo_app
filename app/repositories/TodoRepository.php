<?php

namespace App\Repositories;

use App\Models\Todo;
use App\Entities\TodoEntity;


class TodoRepository
{

    public function getAllByUser(int $userId)
    {
        $todos = Todo::where('user_id', $userId)->get();
        return TodoEntity::collection($todos);
    }
    public function createTodo(TodoEntity $todoEntity, int $userId): TodoEntity
    {
        $todo = Todo::create([
            'user_id' => $userId,
            'title' => $todoEntity->getTitle(),
            'description' => $todoEntity->getDescription(),
            'status' => $todoEntity->getStatus(),
            'priority' => $todoEntity->getPriority(),
            'due_date' => $todoEntity->getDueDate(),
        ]);
        return TodoEntity::fromModel($todo);
    }

    public function updateTodo(Todo $todo, TodoEntity $todoEntity,int $userId): TodoEntity
    {
        $todo->update([
            'user_id' => $userId,
            'title' => $todoEntity->getTitle(),
            'description' => $todoEntity->getDescription(),
            'status' => $todoEntity->getStatus(),
            'priority' => $todoEntity->getPriority(),
            'due_date' => $todoEntity->getDueDate(),
        ]);
        return TodoEntity::fromModel($todo);
    }

    public function getById(Todo $todo): TodoEntity
    {
        return TodoEntity::fromModel($todo);
    }

    public function deleteTodo(Todo $todo): bool
    {
        return $todo->delete();
    }

    public function updateStatus(Todo $todo): TodoEntity
    {
        $todo->update(['status' => ($todo->status == 'complete' ? 'incomplete' : 'complete')]);
        return TodoEntity::fromModel($todo);
    }

    public function updatePriority(Todo $todo, $priority): TodoEntity
    {
        $todo->update(['priority' => $priority]);
        return TodoEntity::fromModel($todo);
    }
}
