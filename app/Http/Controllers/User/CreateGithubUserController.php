<?php

namespace App\Http\Controllers\User;

use App\Console\Commands\GetGithubUserByName;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;

class CreateGithubUserController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(CreateUserRequest $request):UserResource
    {

       $userDto = app(GetGithubUserByName::class)->handle($request->validated('name'));

       $user = User::query()->create([
            'name'            => $userDto->name ?? $request->validated('name'),
            'username'        => $userDto->login,
            'email'           => $userDto->email ?? $request->validated('email'),
            'profileImageUrl' => $userDto->avatar_url ?? $request->validated('profileImageUrl'),
            'bio'             => $userDto->bio ?? $request->validated('bio'),
            'gender'          => $request->validated('gender'),
            'lastname'        => $request->validated('lastname'),
            'password'        => Hash::make($request->validated('password')),
        ]);

        return UserResource::make($user);
    }
}
