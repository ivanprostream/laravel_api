<?php

namespace App\Services;

use App\Models\User;
use App\Http\Resources\UserStoreResource;
use Illuminate\Support\Facades\Hash;

class UserService
{
	public function createUser($request): UserStoreResource
	{
		$user = User::create(
            array_merge(
                $request->only('email', 'password', 'name'),
                ['password' => Hash::make($request->password)]
            )
        );
        return new UserStoreResource($user);
	}
}