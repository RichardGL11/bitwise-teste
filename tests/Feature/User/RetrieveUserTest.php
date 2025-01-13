<?php

use App\Models\User;
use function Pest\Laravel\getJson;

it('should be able to retrieve an user based on his username', function () {
    \Pest\Laravel\withoutExceptionHandling();
   $user = User::factory()->create();

  $request = getJson(route('users.show',$user));

  $request->assertSessionHasNoErrors();
  $request->assertOk();
  $request->assertJsonFragment([
      'data' => [
          'id'              => $user->id,
          'name'            => $user->name,
          'username'        => $user->username,
          'email'           => $user->email,
          'profileImageUrl' => $user->avatar_url,
          'bio'             => $user->bio,
          'gender'          => $user->gender,
          'lastname'        => $user->lastname
      ]
  ]);
});
it('should be able to retrieve an user based on his email', function () {
   $user = User::factory()->create();

  $request = getJson(route('users.email.show',$user));

  $request->assertSessionHasNoErrors();
  $request->assertOk();
  $request->assertJsonFragment([
      'data' => [
          'id'              => $user->id,
          'name'            => $user->name,
          'username'        => $user->username,
          'email'           => $user->email,
          'profileImageUrl' => $user->avatar_url,
          'bio'             => $user->bio,
          'gender'          => $user->gender,
          'lastname'        => $user->lastname
      ]
  ]);
});
