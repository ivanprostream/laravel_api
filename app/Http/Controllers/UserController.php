<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\UserFormRequest;
use App\Http\Resources\UserStoreResource;
use App\Services\UserService;


class UserController extends Controller
{
	private $userService;

	public function __construct( userService $userService )
	{
		$this->userService = $userService;
	}

    public function store(UserFormRequest $request): UserStoreResource
    {
        $user = $this->userService->createUser($request);
        return (new UserStoreResource($user))->additional(['message' => 'User created!']);
    }
}
