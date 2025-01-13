<?php

use App\Enums\GenderEnum;
use App\Models\User;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\patchJson;
use function Pest\Laravel\putJson;

it('should be able to update an existent user', function () {
    $user = User::factory()->createOne([
        'name'                   => 'before',
        'username'               =>  'usernamebefore',
        'lastname'               =>  'lastnamebefore',
        'profileImageUrl'        =>  'https://fastly.picsum.photos/before',
        'bio'                    =>  'my_bio before',
        'gender'                 =>  GenderEnum::FEMALE->value,
    ]);

    $request = putJson(route('users.update', $user),[
        'name'                   => 'joe',
        'username'               =>  'joeusername',
        'lastname'               =>  'doelastname',
        'profileImageUrl'        =>  'https://fastly.picsum.photos/id/227/536/354.jpg?hmac=1jloCASGdPzCfkdYKsHPN_SJWF91dYptz1hBpmEbKI4',
        'bio'                    =>  'my_bio',
        'gender'                 =>  GenderEnum::MALE->value,
    ]);

    assertDatabaseCount(User::class,1);
    assertDatabaseHas(User::class,[
        'id'                     => $user->id,
        'name'                   => 'joe',
        'username'               =>  'joeusername',
        'lastname'               =>  'doelastname',
        'profileImageUrl'        =>  'https://fastly.picsum.photos/id/227/536/354.jpg?hmac=1jloCASGdPzCfkdYKsHPN_SJWF91dYptz1hBpmEbKI4',
        'bio'                    =>  'my_bio',
        'gender'                 =>  GenderEnum::MALE->value,
    ]);

    assertDatabaseMissing(User::class,[
        'name'                   => 'before',
        'username'               =>  'usernamebefore',
        'lastname'               =>  'lastnamebefore',
        'profileImageUrl'        =>  'https://fastly.picsum.photos/before',
        'bio'                    =>  'my_bio before',
        'gender'                 =>  GenderEnum::FEMALE->value,
    ]);

    $user->refresh();
    expect($user->name)
        ->toBe('joe')
        ->and($user->username)
        ->toBe('joeusername')
        ->and($user->lastname)
        ->toBe('doelastname')
        ->and($user->profileImageUrl)
        ->toBe('https://fastly.picsum.photos/id/227/536/354.jpg?hmac=1jloCASGdPzCfkdYKsHPN_SJWF91dYptz1hBpmEbKI4')
        ->and($user->bio)
        ->toBe('my_bio');
});
