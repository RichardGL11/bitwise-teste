<?php

namespace App\Console\Commands;

use App\DataTransferObjects\UserDTO;
use App\Services\Github\Facades\Github;
use App\Services\Github\GithubService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class GetGithubUserByName extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:github-username';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get github profile information by the username ';

    /**
     * Execute the console command.
     */
    public function handle(string $user): UserDTO
    {
       $request = Github::user($user);

       return new UserDTO(
           login: $request['login'],
           name: $request['name'],
           email: $request['email'],
           avatar_url: $request['avatar_url'],
           bio:$request['bio']
       );
    }
}
