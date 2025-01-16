<?php

namespace App\Services\Github;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class GithubService
{
    private PendingRequest $http;
 public function __construct()
 {
     $this->http = Http::withHeaders([
         "Accept: application/vnd.github+json" ,
         "Authorization: Bearer". config('github.token'),
         "X-GitHub-Api-Version: 2022-11-28"
     ]);
 }

 public function user(string $user):array
 {
    $request = $this->http->get("https://api.github.com/users/$user")
        ->throw()
        ->json();

    return $request;
 }
}
