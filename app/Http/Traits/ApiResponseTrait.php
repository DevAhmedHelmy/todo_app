<?php

namespace App\Http\Traits;

use Illuminate\Http\Response;

trait ApiResponseTrait
{
    /**
     * Return a success response.
     *
     * @param  mixed|null  $data
     * @param  int  $statusCode
     * @return \Illuminate\Http\Response
     */
    protected function successResponse($data = null, $statusCode = Response::HTTP_OK)
    {

        $response = [
            'success' => true,
            'data' => $data,
        ];

        return response()->json($response, $statusCode);
    }

    /**
     * Return an error response.
     *
     * @param  string  $message
     * @param  int  $statusCode
     * @return \Illuminate\Http\Response
     */
    protected function errorResponse($message, $statusCode)
    {
        return response()->json([
            'success' => false,
            'errors' => $message,
        ], $statusCode);
    }
}
