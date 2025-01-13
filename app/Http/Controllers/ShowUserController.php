<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;

class ShowUserController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(User $user):UserResource
    {
        return UserResource::make($user);
    }
}
