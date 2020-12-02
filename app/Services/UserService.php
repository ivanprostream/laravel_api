<?php

namespace App\Services;

use App\Models\User;

class UserService
{
	public function createUser($request)
	{
		$user = User::create(
            array_merge(
                $request->all(),
                ['password' => bcrypt($request->password)]
            )
        );

        return $user;
	}
}