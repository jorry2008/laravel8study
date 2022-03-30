<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class TestExceptionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // 个性化重试次数设置
    public $tries = 5;

    // 个性化当前任务执行总时间，超过就失败，通常不设置，表示不限
    public $timeout = 60;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        throw new \Exception('这里是业务逻辑的异常，会导致队列失败并重试'); // 此业务逻辑异常会触发重试机制 attempts 参数更新。
//        $this->fail(new \Exception('这里是业务逻辑的异常，会导致队列失败并重试')); // 不会触发重试机制，但会将此队列列入失败队列 failed_jobs

        Log::info('Test Job 被执行了...');
    }

    public function failed(\Exception $e)
    {
        Log::info('TestExceptionJob 打个日志，记录一下异常：'.$e->getMessage());
    }
}
