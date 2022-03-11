<?php

namespace App\Console;

use App\Console\Commands\RedisPublish;
use App\Jobs\JobTest;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     * 所有的任务调度（定时任务），都写到这里。
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // 1.定义一个闭包调度，这是最简单的调度命令了。
        $schedule->call(function () {
            Log:info(Carbon::now()->toDateTimeLocalString());
            echo '输出测试';
        })->everyMinute(); // ->emailOutputTo('980522557@qq.com')，直接邮件输入，太强大了

        // 2.每分钟执行一次控制台命令 inspire，且此命令是经过路由注入的。
        $schedule->command('inspire')->everyMinute();
//        $schedule->command('redis:publish --force')->daily();
//        $schedule->command(RedisPublish::class, ['--force'])->daily();

        // 3.队列任务调度
        $schedule->job(new JobTest(), 'test', 'database'); // 此功能适合做预期异步任务

        // 4.执行 Shell 命令
//        $schedule->exec('node /home/forge/script.js')->daily();

        // 后面的时间定义直接 查表 即可
        // 额外功能包括：执行条件、环境限制、时区、任务执行去重、多服务器合并、后台执行、维护模式执行、任务信息输出到指定文件或发邮件、
        // 任务色子before after、Pinging 网址（可用来执行第三方统计）
    }

    /**
     * Register the commands for the application.
     * 这个是用来注册控制台命令的，有两种形式：
     * 1.开发命令行类 App\Console\Commands;
     * 2.注册控制台路由
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

    /**
     * 获取计划事件默认使用的时区
     *
     * @return \DateTimeZone|string|null
     */
//    protected function scheduleTimezone()
//    {
//        return 'America/Chicago';
//    }
}
