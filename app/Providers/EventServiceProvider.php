<?php

namespace App\Providers;

use App\Listeners\UserSubscribeListener;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Notifications\Events\NotificationSending;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */

    // 监听
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        // 先注册,后使用 php artisan event:generate 自动生成两个类
        // 事件队列化
//        'App\Events\UserTestEvent' => [
//            'App\Listeners\UserTestListener',
//        ],

        // 消息通知监听器注入
        'Illuminate\Notifications\Events\NotificationSending' => [
            'App\Listeners\LogNotification',
        ],

        // 任务调度监听器注入
        'Illuminate\Console\Events\ScheduledTaskStarting' => [
            'App\Listeners\ScheduleListener',
        ],
    ];

    // 订阅
    protected $subscribe = [
//        UserDeletingEvent::class => UserSubscribeListener::class,
        UserSubscribeListener::class,
    ];

    // 开启事件自动发现功能
//    public function shouldDiscoverEvents()
//    {
//        return true;
//    }

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
//        User::observe(UserObserver::class);
        //
    }
}
