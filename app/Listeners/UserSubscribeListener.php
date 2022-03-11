<?php

namespace App\Listeners;

use App\Models\User;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Event;

class UserSubscribeListener
{
    /**
     * 处理用户登录事件。
     */
    public function handleUserLogin($event) {
//        dd('登录');
    }

    /**
     * 处理用户退出事件。
     */
    public function handleUserLogout($event) {
//        dd('退出');
    }

    /**
     * 为订阅者注册侦听器。
     *
     * @param  \Illuminate\Events\Dispatcher $events
     * @return void
     */
    public function subscribe(Dispatcher $events)
    {
        $events->listen(
            'Illuminate\Auth\Events\Login',
            [UserSubscribeListener::class, 'handleUserLogin']
        );

        $events->listen(
            'Illuminate\Auth\Events\Logout',
            [UserSubscribeListener::class, 'handleUserLogout']
        );
    }
}
