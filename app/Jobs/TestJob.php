<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class TestJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels; // InteractsWithQueue 使队列具备交互能力

    // 个性化设置队列
//    public $queue = 'test';

    // 个性化设置队列连接
//    public $connection = 'database';

    // 个性化重试次数设置
    public $tries = 10;

    // 个性化当前任务执行总时间，超过就失败，通常不设置，表示不限
    public $timeout = 60; // 超时，直接导致队列进程退出

    // 个性化设置，当前任务执行失败后，自动延迟执行的时间，同命令行 --delay 一样
    public $delay = 5;

    // 个性化设置当前任务异常的最大次数（默认不限次数）
    public $maxExceptions = 3;

    // 个性化设置
//    public $timeoutAt = 2; // 超时，会导致job执行失败并异常，但队列进程是正常的，此值应该比队列的 retry_after 小，更安全

    // 个性化设置
    // 同：队列参数 retry_after，
//    public function retryUntil()
//    {
//        return now()->addMinutes(5); // 当前任务，最大执行时间为 5 分钟（包括重试时间），超时自动释放进入 failed_jobs 任务
//    }

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        // 个性化指定队列连接和队列
//        $this->onConnection();
//        $this->onQueue();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        sleep(3); // 超时测试

        $this->release(0); // 这个作用非常大，表示：继续执行任务，还可以指定下次执行前延期多长时间【为0时，表示马上进入下一次执行】

        Log::info('Test Job 被执行了...');
    }

    public function failed(\Exception $e)
    {
        Log::info('TestJob 打个日志，记录一下异常：'.$e->getMessage());
    }
}
