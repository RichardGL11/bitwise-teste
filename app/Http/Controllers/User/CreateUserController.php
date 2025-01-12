<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class CreateUserController extends Controller
{
    public function __invoke(CreateUserRequest $request): JsonResponse
    {
            User::query()->create([
                'name'              => $request->validated('name'),
                'email'             => $request->validated('email'),
                'password'          =>  Hash::make($request->validated('password')),
                'username'          => $request->validated('username'),
                'lastname'          => $request->validated('lastname'),
                'profileImageUrl'   => $request->validated('profileImageUrl'),
                'bio'               => $request->validated('bio'),
                'gender'            => $request->validated('gender'),
            ]);

            return response()->json(['message' => 'user created successfully'],201);
    }
}
