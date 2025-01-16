<?php

use App\Enums\GenderEnum;
use App\Models\User;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\postJson;
use function Pest\Laravel\withoutExceptionHandling;


it('should be able to register an user to the using the github informations application', function (){
    withoutExceptionHandling();

    Http::fake([
        "https://api.github.com/users/joe" => Http::response([
               "login"       => "joe",
                "name"       => "joe",
                "email"      => "joe@doe.com",
                "avatar_url" => "https://avatars.githubusercontent.com/u/787?v=4",
                "bio"        => "my_bio"
        ])
    ]);

    $request = postJson(route('users.github.store',[
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
        'username'          =>  'joe'    ,
        'lastname'          =>  'doelastname'    ,
        'bio'               =>  'my_bio',
        'profileImageUrl'   => "https://avatars.githubusercontent.com/u/787?v=4",
        'gender'            =>  GenderEnum::MALE->value,
        'email'             => 'joe@doe.com',
    ]);

    $user = User::query()->first();
    expect($user->name)->toBe('joe')
        ->and($user->username)->toBe('joe')
        ->and($user->lastname)->toBe('doelastname')
        ->and($user->profileImageUrl)->toBe("https://avatars.githubusercontent.com/u/787?v=4")
        ->and($user->bio)->toBe('my_bio')
        ->and($user->email)->toBe('joe@doe.com');

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



it('should throws exception if an user is not found'  , function () {
 withoutExceptionHandling();
 Http::fake([
     "https://api.github.com/users/joe" => Http::throw(function (){
       throw new RequestException();
     })
 ]);
    $request = postJson(route('users.github.store',[
        'name'                   => 'ahahahahahahshdajda',
        'username'               =>  'joeusername',
        'lastname'               =>  'doelastname',
        'profileImageUrl'        =>  'https://fastly.picsum.photos/id/227/536/354.jpg?hmac=1jloCASGdPzCfkdYKsHPN_SJWF91dYptz1hBpmEbKI4',
        'bio'                    =>  'my_bio',
        'gender'                 =>  GenderEnum::MALE->value,
        'email'                  => 'joe@doe.com',
        'password'               => 'password',
        'password_confirmation'  => 'password'
    ]));

})->throws(RequestException::class);
