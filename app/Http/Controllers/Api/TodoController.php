<?php

namespace App\Http\Controllers\Api;

use App\DTO\TodoDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\TodoRequest;
use App\Http\Resources\TodoResource;
use App\Http\Traits\ApiResponseTrait;
use App\Library\ResourcePaginator;
use App\Models\Todo;
use App\Services\TodoService;
use Illuminate\Http\{Request, Response};
use Illuminate\Support\Facades\Validator;

class TodoController extends Controller
{
    use ApiResponseTrait;

    private $todoService;
    public function __construct(TodoService $todoService)
    {
        $this->todoService = $todoService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->successResponse(TodoResource::collection($this->todoService->getAllByUser()));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TodoRequest $request)
    {

        $dto = TodoDto::fromRequest($request->validated());
        $todo = $this->todoService->createTodo($dto);
        return $this->successResponse($todo, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Todo $todo)
    {
        $todo = $this->todoService->getById($todo);
        return $this->successResponse(new TodoResource($todo));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TodoRequest $request, Todo $todo)
    {

        $dto = TodoDto::fromRequest($request->validated());
        $todo = $this->todoService->updateTodo($todo, $dto);
        return $this->successResponse(new TodoResource($todo));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Todo $todo)
    {
        $this->todoService->deleteTodo($todo);
        return $this->successResponse();
    }

    public function updateStatus($id)
    {
        $todo = $this->todoService->updateStatus($id);
        return $this->successResponse(new TodoResource($todo));
    }

    public function updatePriority(Request $request, $id)
    {
        // validate
        $validator = Validator::make($request->all(), [
            'priority' => ['required', 'in:low,medium,high'],
        ]);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $todo = $this->todoService->updatePriority($id, $request->priority);
        return $this->successResponse(new TodoResource($todo));
    }
}
