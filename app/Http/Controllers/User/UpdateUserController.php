<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UpdateUserController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(UpdateUserRequest $request, User $user)
    {
        $user->name = $request->validated('name');
        $user->username = $request->validated('username');
        $user->lastname = $request->validated('lastname');
        $user->profileImageUrl = $request->validated('profileImageUrl');
        $user->bio = $request->validated('bio');
        $user->gender = $request->validated('gender');
        $user->save();
    }
}
