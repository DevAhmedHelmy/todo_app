<?php

namespace App\Repositories;

use App\Models\Todo;
use App\Entities\TodoEntity;


class TodoRepository
{
    public function count(int $userId)
    {
        return Todo::query()->where('user_id', $userId)->count();
    }
    public function getAllByUser(int $userId, $limit, $page)
    {
        $todos= Todo::query()
            ->where('user_id', $userId)
            ->with('user')
            ->limit($limit)
            ->offset(($page - 1) * $limit)
            ->orderBy('id')
            ->get()
            ->map(function (Todo $todo) {
                return TodoEntity::fromModel($todo);
            });
        return $todos;

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

    public function updateTodo(Todo $todo, TodoEntity $todoEntity, int $userId): TodoEntity
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
