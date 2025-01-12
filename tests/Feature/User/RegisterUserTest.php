<?php

use App\Enums\GenderEnum;
use App\Models\User;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\postJson;
use function Pest\Laravel\withoutExceptionHandling;

it('should be able to register an user to the application', function (){
    withoutExceptionHandling();

    $request = postJson(route('users.store',[
        'name'                   => 'joe',
        'username'               =>  'joeusername',
        'lastname'               =>  'doelastname',
        'profileImageUrl'        =>  'https://fastly.picsum.photos/id/227/536/354.jpg?hmac=1jloCASGdPzCfkdYKsHPN_SJWF91dYptz1hBpmEbKI4',
        'bio'                    =>  'my_bio',
        'gender'                 =>  GenderEnum::MALE->value,
        'email'                  => 'joe@doe.com',
        'password'               => 'password',
        'password_confirmation'  => 'password'
    ]));

    $request->assertSessionHasNoErrors();
    $request->assertCreated();

    assertDatabaseCount(User::class,1);
    assertDatabaseHas(User::class,[
        'name'              => 'joe',
        'username'          =>  'joeusername'    ,
        'lastname'          =>  'doelastname'    ,
        'profileImageUrl'   =>  'https://fastly.picsum.photos/id/227/536/354.jpg?hmac=1jloCASGdPzCfkdYKsHPN_SJWF91dYptz1hBpmEbKI4',
        'bio'               =>  'my_bio',
        'gender'            =>  GenderEnum::MALE->value,
        'email'             => 'joe@doe.com',
    ]);

   $user = User::query()->first();
    expect($user->name)->toBe('joe')
        ->and($user->username)->toBe('joeusername')
        ->and($user->lastname)->toBe('doelastname')
        ->and($user->profileImageUrl)->toBe('https://fastly.picsum.photos/id/227/536/354.jpg?hmac=1jloCASGdPzCfkdYKsHPN_SJWF91dYptz1hBpmEbKI4')
        ->and($user->bio)->toBe('my_bio')
        ->and($user->email)->toBe('joe@doe.com');

    $request->assertJsonFragment(['message' => 'user created successfully']);
});
