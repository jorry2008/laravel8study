<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class RetryAfterTestJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 0; // 不限重试次数

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    // 方案一：可无限重试，设置当前任务一个固定的寿命，即：最大执行时间为 5 分钟（包括重试时间），超时自动释放进入 failed_jobs 任务
    // 就是说，给任务执行总时间为 5 分钟，不关注重试了多少次
    public function retryUntil()
    {
        return now()->addSeconds(30);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('第 '.$this->attempts().' 次执行开始');
        sleep(3); // 模拟执行 3 秒
        Log::info('第 '.$this->attempts().' 次业务逻辑执行完成');
        $this->release(2);
//        $this->release(0); // 表示继续执行的意思，且不需要延时！！！
    }

    // 结论：->retryUntil()方法返回的值与 retry_after 值是同一个，
    // 表示，任务的执行时长 t + 每次的延迟 delay 时长，再重试 N 次的总控制时间，即 retry_after = (t + delay)*N，超时则异常，任务失败

    // 自定义处理队列异常信息
    public function failed(\Exception $e)
    {
        Log::error(static::class.' 队列异常信息：'.$e->getMessage());
    }
}
