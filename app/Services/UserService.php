<?php

namespace App\Services;

use App\Models\User;

class UserService
{
	public function createUser($request): object
	{
		$user = User::create(
            array_merge(
                $request->only('email', 'password', 'name'),
                ['password' => bcrypt($request->password)]
            )
        );
        return $user;
	}
}