<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\TodoRequest;
use App\Http\Resources\TodoResource;
use App\Http\Traits\ApiResponseTrait;
use App\Library\ResourcePaginator;
use Illuminate\Http\{Request, Response};
use Illuminate\Support\Facades\Validator;

class TodoController extends Controller
{
    use ApiResponseTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $todos = $request->user('api')->todos()->paginate(10);
        $collection = new ResourcePaginator(TodoResource::collection($todos));
        return $this->successResponse($collection);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TodoRequest $request)
    {
        $todo = $request->user('api')->todos()->create($request->validated());
        return $this->successResponse(new TodoResource($todo), Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
        $todo  = $request->user('api')->todos()->findOrFail($id);

        return $this->successResponse(new TodoResource($todo));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TodoRequest $request, $id)
    {
        $todo =  $request->user('api')->todos()->findOrFail($id);
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
        $todo = auth()->user('api')->todos()->findOrFail($id);
        $todo->delete();

        return $this->successResponse();
    }

    public function updateStatus($id)
    {
        $todo =  auth()->user('api')->todos()->findOrFail($id);
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
        $todo = $todo = auth()->user('api')->todos()->findOrFail($id);
        $todo->update(['priority' => $request->priority]);

        return $this->successResponse($todo);
    }
}
