<?php

namespace App\DataTransferObjects;

final readonly class UserDTO
{
    public function __construct(
        public string $login,
        public ?string $name,
        public ?string $email,
        public ?string $avatar_url,
        public ?string $bio
    )
    {}
}
