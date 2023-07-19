<?php

namespace App\Repositories;

use App\Models\Todo;
use App\Entities\TodoEntity;

class TodoRepository
{
    public function getAll()
    {
        $todos=  auth()->user('api')->todos()->get();
        return TodoEntity::collection($todos);
    }
    public function createTodo(TodoEntity $todoEntity): TodoEntity
    {
        $todo = Todo::create([
            'user_id' => auth('api')->id(),
            'title' => $todoEntity->getTitle(),
            'description' => $todoEntity->getDescription(),
            'status' => $todoEntity->getStatus(),
            'priority' => $todoEntity->getPriority(),
            'due_date' => $todoEntity->getDueDate(),
            'created_at' => $todoEntity->getCreatedAt(),
            'updated_at' => $todoEntity->getUpdatedAt(),
        ]);
        return TodoEntity::fromModel($todo);
    }

    public function updateTodo(int $id, TodoEntity $todoEntity): TodoEntity
    {
        $todo = Todo::findOrFail($id);
        $todo->update([
            'user_id' => auth('api')->id(),
            'title' => $todoEntity->getTitle(),
            'description' => $todoEntity->getDescription(),
            'status' => $todoEntity->getStatus(),
            'priority' => $todoEntity->getPriority(),
            'due_date' => $todoEntity->getDueDate(),
            'created_at' => $todoEntity->getCreatedAt(),
            'updated_at' => $todoEntity->getUpdatedAt(),
        ]);
        return TodoEntity::fromModel($todo);
    }

    public function getById(int $id): TodoEntity
    {
        $todo = Todo::findOrFail($id);
        return TodoEntity::fromModel($todo);
    }

    public function deleteTodo(int $id): bool
    {
       return Todo::findOrFail($id)->delete();
    }

    public function updateStatus(int $id) : TodoEntity
    {
        $todo = Todo::findOrFail($id);
        $status = $todo->status == 'complete' ? 'incomplete' : 'complete';
        $todo->update(['status' => $status]);
        return TodoEntity::fromModel($todo);
    }

    public function updatePriority(int $id, $priority): TodoEntity
    {
        $todo = Todo::findOrFail($id);
        $todo->update(['priority' => $priority]);
        return TodoEntity::fromModel($todo);
    }
}
