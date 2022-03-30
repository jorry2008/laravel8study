<?php

namespace App\Jobs\Middleware;

class RateLimited
{
    public function middleware()
    {
        return [new \Illuminate\Queue\Middleware\RateLimited()];
    }
}
