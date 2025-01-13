<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
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
