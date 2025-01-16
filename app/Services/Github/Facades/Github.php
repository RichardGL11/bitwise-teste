<?php

namespace App\Services\Github\Facades;


use App\Services\Github\GithubService;
use Illuminate\Support\Facades\Facade;

class Github extends Facade
{
 protected static function getFacadeAccessor(): string
 {
     return GithubService::class;
 }
}
