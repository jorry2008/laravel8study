<?php

namespace App\Jobs\Meetting;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class MessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3; // 不限重试次数

    public $timeout = 5*60; // 5分钟

    public function retryUntil()
    {
        return now()->addMinutes(3);
    }

    protected $userOrder;

    public function __construct(UserOrder $userOrder)
    {
        //
    }

    public function handle()
    {
        // 发送各种消息...
    }

    // 自定义处理队列异常信息
    public function failed(\Exception $e)
    {
        Log::error(static::class.' 队列异常信息：'.$e->getMessage());
    }
}
