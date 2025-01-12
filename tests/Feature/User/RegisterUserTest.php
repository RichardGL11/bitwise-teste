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

describe('validation tests', function (){

    test('name::validations',function ($rule, $value){
        $request = postJson(route('users.store',[
            'name'                   => $value,
            'username'               =>  'joeusername',
            'lastname'               =>  'doelastname',
            'profileImageUrl'        =>  'https://fastly.picsum.photos/id/227/536/354.jpg?hmac=1jloCASGdPzCfkdYKsHPN_SJWF91dYptz1hBpmEbKI4',
            'bio'                    =>  'my_bio',
            'gender'                 =>  GenderEnum::MALE->value,
            'email'                  => 'joe@doe.com',
            'password'               => 'password',
            'password_confirmation'  => 'password'
        ]));

        $request->assertJsonValidationErrors(['name' => $rule]);
    })->with([
        'required'          => ["The name field is required.", ''],
        'min'               => ['The name field must be at least 3 characters.', 'A'],
        'max'               => ['The name field must not be greater than 30 characters.', str_repeat('a',31)],
        'alpha_ascii'       => ['The name field must only contain letters.', 'jane.doe']
    ]);
 test('username::validations',function ($rule, $value){
         User::factory()->createOne(['username' =>'username']);
        $request = postJson(route('users.store',[
            'name'                   => 'qualquer',
            'username'               =>  $value,
            'lastname'               =>  'doelastname',
            'profileImageUrl'        =>  'https://fastly.picsum.photos/id/227/536/354.jpg?hmac=1jloCASGdPzCfkdYKsHPN_SJWF91dYptz1hBpmEbKI4',
            'bio'                    =>  'my_bio',
            'gender'                 =>  GenderEnum::MALE->value,
            'email'                  => 'joe@doe.com',
            'password'               => 'password',
            'password_confirmation'  => 'password'
        ]));

        $request->assertJsonValidationErrors(['username' => $rule]);
    })->with([
        'required'   => ["The username field is required.", ''],
        'min'        => ['The username field must be at least 5 characters.', 'A'],
        'max'        => ['The username field must not be greater than 30 characters.', str_repeat('a',31)],
        'alpha_dash' => ['The username field must only contain letters, numbers, dashes, and underscores.', 'jane.doe'],
        'unique'     =>  ['The username has already been taken.', 'username' ]
    ]);

  test('lastname::validations',function ($rule, $value){
        $request = postJson(route('users.store',[
            'name'                   => 'qualquer',
            'username'               =>  'username',
            'lastname'               =>  $value,
            'profileImageUrl'        =>  'https://fastly.picsum.photos/id/227/536/354.jpg?hmac=1jloCASGdPzCfkdYKsHPN_SJWF91dYptz1hBpmEbKI4',
            'bio'                    =>  'my_bio',
            'gender'                 =>  GenderEnum::MALE->value,
            'email'                  => 'joe@doe.com',
            'password'               => 'password',
            'password_confirmation'  => 'password'
        ]));

        $request->assertJsonValidationErrors(['lastname' => $rule]);
    })->with([
        'min'        => ['The lastname field must be at least 5 characters.', 'A'],
        'max'        => ['The lastname field must not be greater than 30 characters.', str_repeat('a',31)],
        'alpha_dash' => ['The lastname field must only contain letters, numbers, dashes, and underscores.', 'jane.doe'],
    ]);


  test('profileImageUrl::validations',function ($rule, $value){
        $request = postJson(route('users.store',[
            'name'                   => 'qualquer',
            'username'               =>  'username',
            'lastname'               =>  'lastname',
            'profileImageUrl'        =>  $value,
            'bio'                    =>  'my_bio',
            'gender'                 =>  GenderEnum::MALE->value,
            'email'                  => 'joe@doe.com',
            'password'               => 'password',
            'password_confirmation'  => 'password'
        ]));

        $request->assertJsonValidationErrors(['profileImageUrl' => $rule]);
    })->with([
        'http' => ['The profile image url field must be a valid URL.', 'jane.doe'],
    ]);


    test('bio::validations',function ($rule, $value){
        $request = postJson(route('users.store',[
            'name'                   => 'qualquer',
            'username'               =>  'username',
            'lastname'               =>  'lastname',
            'profileImageUrl'        =>  'https://fastly.picsum.photos/id/227/536/354.jpg?hmac=1jloCASGdPzCfkdYKsHPN_SJWF91dYptz1hBpmEbKI4',
            'bio'                    =>  $value,
            'gender'                 =>  GenderEnum::MALE->value,
            'email'                  => 'joe@doe.com',
            'password'               => 'password',
            'password_confirmation'  => 'password'
        ]));

        $request->assertJsonValidationErrors(['bio' => $rule]);
    })->with([
        'min'        => ['The bio field must be at least 5 characters.', 'A'],
        'max'        => ['The bio field must not be greater than 30 characters.', str_repeat('a',31)],
        'alpha_dash' => ['The bio field must only contain letters, numbers, dashes, and underscores.', 'jane.doe'],
    ]);
    test('gender::validations',function ($rule, $value){
        $request = postJson(route('users.store',[
            'name'                   => 'qualquer',
            'username'               =>  'username',
            'lastname'               =>  'lastname',
            'profileImageUrl'        =>  'https://fastly.picsum.photos/id/227/536/354.jpg?hmac=1jloCASGdPzCfkdYKsHPN_SJWF91dYptz1hBpmEbKI4',
            'bio'                    =>  'bioqualquer',
            'gender'                 =>  $value,
            'email'                  => 'joe@doe.com',
            'password'               => 'password',
            'password_confirmation'  => 'password'
        ]));

        $request->assertJsonValidationErrors(['gender' => $rule]);
    })->with([
        'required'   => ["The gender field is required.", ''],
        'enum'       => ['The selected gender is invalid.', 'jane.doe'],
    ]);
    test('email::validations',function ($rule, $value){
        $request = postJson(route('users.store',[
            'name'                   => 'qualquer',
            'username'               =>  'username',
            'lastname'               =>  'lastname',
            'profileImageUrl'        =>  'https://fastly.picsum.photos/id/227/536/354.jpg?hmac=1jloCASGdPzCfkdYKsHPN_SJWF91dYptz1hBpmEbKI4',
            'bio'                    =>  'bioqualquer',
            'gender'                 =>  GenderEnum::MALE->value,
            'email'                  => $value,
            'password'               => 'password',
            'password_confirmation'  => 'password'
        ]));

        $request->assertJsonValidationErrors(['email' => $rule]);
    })->with([
        'required'   => ["The email field is required.", ''],
        'email'      => ['The email field must be a valid email address.', 'jane.doe'],
    ]);

    test('password::validations',function ($rule, $value){
        $request = postJson(route('users.store',[
            'name'                   => 'qualquer',
            'username'               =>  'username',
            'lastname'               =>  'lastname',
            'profileImageUrl'        =>  'https://fastly.picsum.photos/id/227/536/354.jpg?hmac=1jloCASGdPzCfkdYKsHPN_SJWF91dYptz1hBpmEbKI4',
            'bio'                    =>  'bioqualquer',
            'gender'                 =>  GenderEnum::MALE->value,
            'email'                  => 'joe@doe.com',
            'password'               => $value,
            'password_confirmation'  => 'password'
        ]));

        $request->assertJsonValidationErrors(['password' => $rule]);
    })->with([
        'required'      => ["The password field is required.", ''],
        'confirmed'      => ['The password field confirmation does not match.', 'jane.doe'],
    ]);


});
