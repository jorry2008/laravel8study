<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class BreakOffTestJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 0; // 不限重试次数

//    public $timeout = 10;

    public function __construct()
    {
        //
    }

    public function retryUntil()
    {
        return now()->addSeconds(5); // 默认 60
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('第 '.$this->attempts().' 次执行开始');


        sleep(12);


        Log::info('第 '.$this->attempts().' 次业务逻辑执行完成');
//        $this->release(2);
        $this->release(0); // 表示继续执行的意思，且不需要延时！！！
    }

    // 自定义处理队列异常信息
    public function failed(\Exception $e)
    {
        Log::error(static::class.' 队列异常信息：'.$e->getMessage());
    }
}
