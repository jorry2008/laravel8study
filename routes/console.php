<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| 控制台路由，用来定义控制台命令
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());

    // 显示一个进度条
    $users = App\Models\User::all();
    $this->withProgressBar($users, function ($value, $bar) {
        // ...
    });

})->purpose('Display an inspiring quote');
