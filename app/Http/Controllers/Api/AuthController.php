<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Http\Resources\AuthResource;
use App\Http\Traits\ApiResponseTrait;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    use ApiResponseTrait;

    public function register(RegisterRequest $request)
    {
        $request['password'] = Hash::make($request['password']);
        $request['remember_token'] = Str::random(10);
        $user = User::create($request->toArray());

        return $this->response($user, 201);
    }

    public function login(LoginRequest $request)
    {
        $user = $request->authenticate();

        return $this->response($user);
    }

    public function logout(Request $request)
    {
        $token = $request->user()->token();
        $token->revoke();
        $response = ['message' => 'You have been successfully logged out!'];

        return response($response, 200);
    }

    /**
     * A description of response function.
     *
     * @param  User  $user
     * @return response
     */
    private function response($user, $code = 200)
    {
        $token = $user->createToken('Laravel Password Grant Client')->accessToken;
        $data = ['user' => new AuthResource($user), 'access_token' => $token];

        return $this->successResponse($data, $code);
    }
}
