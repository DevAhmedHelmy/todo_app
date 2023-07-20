<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TodoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->getId(),
            'title' => $this->getTitle(),
            'description' => $this->getDescription(),
            'due_date' => $this->getDueDate(),
            'priority' => $this->getPriority(),
            'status' => $this->getStatus(),
            'user name' => $this->getUser()->getName(),

        ];
    }
}
