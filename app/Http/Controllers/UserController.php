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

    public function store(UserFormRequest $request): void
    {
        $this->userService->createUser($request);
    }
}
