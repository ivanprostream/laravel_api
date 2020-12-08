<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\AuthRequest;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function login(AuthRequest $request): object
    {
        $validatedData = $request->validated();

        $token_validity = (24 * 60);

        $this->guard()->factory()->setTTL($token_validity);

        if (!$token = $this->guard()->attempt($validatedData)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);

    }

    public function logout(): object
    {
        $this->guard()->logout();

        return response()->json(['message' => 'User logged out successfully']);
    }


    public function profile(): object
    {
        return response()->json($this->guard()->user());
    }


    public function refresh(): string
    {
        return $this->respondWithToken($this->guard()->refresh());

    }


    protected function respondWithToken($token): object
    {
        return response()->json(
            [
                'token'          => $token,
                'token_type'     => 'bearer',
                'token_validity' => ($this->guard()->factory()->getTTL() * 60),
            ]
        );
    }


    protected function guard()
    {
        return Auth::guard();

    }


}//end class