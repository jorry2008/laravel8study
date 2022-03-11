<?php

namespace App\Listeners;

use App\Events\UserTestEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class UserTestListener implements ShouldQueue // 自动转化为队列
{
//    use InteractsWithQueue, Queueable; // 可以在当前类中配置所有与队列相关的属性

    public $connection = 'database';
    public $queue = 'event_queue';
    public $afterCommit = true;

    public function attempts()
    {
        return 3;
    }

    public function fail($exception = null)
    {
        //
    }

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\UserTestEvent  $event
     * @return void
     */
    public function handle(UserTestEvent $event)
    {
//        dd($event->user->name);
//        dd($event->user->email);
        Log::info('debug', [$event->user->name, $event->user->email]);
    }
}
