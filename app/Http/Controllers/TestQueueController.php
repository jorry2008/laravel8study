<?php

namespace App\Http\Controllers;

use App\Jobs\BreakOffTestJob;
use App\Jobs\ConcurrentTestJob;
use App\Jobs\Order\MonitorPendingOrderJob;
use App\Jobs\ReportJob;
use App\Jobs\ReportTask1Job;
use App\Jobs\ReportTask2Job;
use App\Jobs\ReportTask3Job;
use App\Jobs\Request\ThirdRequestJob;
use App\Jobs\RetryAfterTestJob;
use App\Jobs\TestJob;
use App\Mail\TestMail;
use Illuminate\Mail\Mailer;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class TestQueueController extends Controller
{
    public function testJob()
    {
        // 关于队列异常和异常传递
        // 1.在队列handle中抛出异常，即队列执行失败，随后但尝试执行指定的次数。
        // 2.如果不想队列在异常时卡壳，比如：邮箱队列，这种队列任务之间无执行顺序时，可以采用fail将队列直接失败进入failed_jobs，且可以function failed()方法捕获异常。
        // 3.当队列任务业务逻辑有异常抛出时，对应的任务将删除，并重新新建，available_at 和 created_at 同时更新为当前时间。

        // 快捷入队列的方法，直接由控制器支持，来自 trait DispatchesJobs
//        $this->dispatch();

        // web应用响应完成后，马上触发此执行。它与同步唯一的区别在于，它放在业务代码完成后，关闭服务器时触发执行，即，永远放在最后执行。
//       TestJob::dispatchAfterResponse(); // 这是响应执行，即当前请求运行完成后立马执行，不属于异步队列（测试的效果：不会进入队列，也没有队列相关的方法可以调用，直接会被触发，与同步非常类似）
//       TestJob::dispatchNow(); // 这是同步执行，不属于异步队列

        // 基本参数说明：
//        TestJob::dispatch()
//            ->onConnection('database')
//            ->onQueue('job')
//            ->delay(now()->addSeconds(10));

        // 队列的时序性验证【没有时序性，只是按照创建的顺序执行而已】
//        TestJob::dispatch()->onQueue('job');
//        TestExceptionJob::dispatch()->onQueue('job');
//        TestJob::dispatch()->onQueue('job');


//        TestJob::dispatch()->onQueue('job')->delay(now()->addSeconds(10)); // 延时 10s
        TestJob::dispatch()->onQueue('job')->delay(now()->addSeconds(10));

        echo '测试队列 test job -> '.now()->toDateTimeString();
    }

    public function testEmail()
    {
        // 创建 markdown 邮件的方式：php artisan make:mail TestMail --markdown=emails.test_mail
        // 非队列的那些html或plain、raw的方式发送，可以直接查看手册，比较简单。
        // Mail::mailer('smtp1')->send((new TestMail())->to('980522557@qq.com', 'jorry'));

        // 第一种，隐性队列发送，邮件实现了ShouldQueue将自动以队列的方式发信息 // 正常的发送，也会丢进指定队列
        // Mail::mailer('smtp1')->send((new TestMail())->onConnection('database')->onQueue('email'));

        // 第二种，以显性队列的方式发送邮件
        $message = new TestMail();
        $message->onConnection('database'); // 注意：onConnection，onQueue 两个方法不能在 Mailable 中的 build() 构建
        $message->onQueue('email');
//        $message->locale('en_US');
//        $message->delay(now()->addSeconds(10));
//        $message->later(10);
        Mail::mailer('smtp1')->send($message); // ->queue($message)
        if (count(Mail::failures()) < 1) {
            echo '发送成功...';
        }

        echo '测试队列邮箱 -> '.now()->toDateTimeString();
    }

    // 链接、队列中的各种特性
    public function TestReport()
    {
        // 主任务和子任务
        // 注意：所有子任务，从逻辑上表现为顺序性！！！
        // 1.如果有多个主子任务，队列优先执行主任务，然后统一执行每个主任务中的第一个子任务，依次批量执行子任务，直到一次性所有的任务执行完成。
        // 2.通常在这种链接模式中，主任务没有异常逻辑，同一个主任务中的所有子任务中任何一个子任务fail了，后面的所有子任务将不再执行，整个主任务标记为失败。
        ReportJob::withChain([
            new ReportTask1Job(),
            new ReportTask2Job(),
            new ReportTask3Job(),
        ])->dispatch();

        echo '测试队列链接 -> '.now()->toDateTimeString();

    }

    // 自动取消订单方案
    public function TestOrder()
    {
        // 自动退单发消息测试
//        MonitorPendingOrderJob::dispatch()->delay(now()->addMinutes(60)); // 60分钟后开始执行自动取消订单操作逻辑

        // 第三方服务请求测试
//        ThirdRequestJob::dispatch()->delay(now()->seconds(5)); // 5秒后开始执行网络请求

        // retryafter 参数测试
//        RetryAfterTestJob::dispatch(); // ->delay(now()->seconds(5));
//        BreakOffTestJob::dispatch();

        // 并发幂等测试
        ConcurrentTestJob::dispatch();

        echo '入队成功 -> '.now()->toDateTimeString();
    }
}
