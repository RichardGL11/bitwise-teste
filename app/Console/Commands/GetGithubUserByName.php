<?php

namespace App\Console\Commands;

use App\DataTransferObjects\UserDTO;
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
       $request =  Http::withHeaders([
            "Accept: application/vnd.github+json" ,
            "Authorization: Bearer". config('github.token'),
            "X-GitHub-Api-Version: 2022-11-28"
        ])
        ->get("https://api.github.com/users/$user")
        ->throw()
        ->json();

       return new UserDTO(
           login: $request['login'],
           name: $request['name'],
           email: $request['email'],
           avatar_url: $request['avatar_url'],
           bio:$request['bio']
       );
    }
}
