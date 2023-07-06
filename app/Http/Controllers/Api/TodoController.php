<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\TodoRequest;
use App\Http\Resources\TodoResource;
use App\Http\Traits\ApiResponseTrait;
use App\Library\ResourcePaginator;
use App\Models\Todo;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class TodoController extends Controller
{
    use ApiResponseTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $todos = Todo::byUser()->paginate(10);
        $collection = new ResourcePaginator(TodoResource::collection($todos));
        return $this->successResponse($collection);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TodoRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth('api')->id();
        $todo = Todo::create($data);
        $todo->refresh();

        return $this->successResponse(new TodoResource($todo), Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $todo = $this->getTodo($id);

        return $this->successResponse(new TodoResource($todo));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TodoRequest $request, $id)
    {
        $todo = $this->getTodo($id);
        $data = $request->all();
        $data['user_id'] = auth('api')->id();
        $todo->update($data);

        return $this->successResponse(new TodoResource($todo));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $todo = $this->getTodo($id);
        $todo->delete();

        return $this->successResponse();
    }

    public function updateStatus($id)
    {
        $todo = $this->getTodo($id);
        $status = $todo->status == 'complete' ? 'incomplete' : 'complete';
        $todo->update(['status' => $status]);

        return $this->successResponse($todo);
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
        $todo = $this->getTodo($id);
        $todo->update(['priority' => $request->priority]);

        return $this->successResponse($todo);
    }

    private function getTodo($id)
    {
        $todo = Todo::byUser()->find($id);
        if (! $todo) {
            throw new HttpResponseException($this->errorResponse('Todo not found', Response::HTTP_NOT_FOUND));
        }

        return $todo;
    }
}
