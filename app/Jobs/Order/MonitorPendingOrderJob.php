<?php

namespace App\Jobs\Order;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class MonitorPendingOrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 4; // 最大重试这4次，即用户在未支付订单状态时，最多允许给用户发三条信息，共四个时间节点：
    // 60(开始执行,并发消息) delay 60
    // 60+10(发消息) delay 10
    // 60+10+10(发消息) delay 10
    // 60+10+10+10(取消并释放)

    // 自动取消订单的功能，也可以由 $delay 或 --delay 参数来实现，即任务失败自动延迟重试
//    public $delay = 10*60;

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
        if (true) { // ($this->attempts() < 3) { // 订单未支付，模拟催单第二次之后，用户下单了【这里是取订单当前状态】
            if ($this->attempts() > 3) { // 最后一次
                Log::info('取消订单并释放库存...'); // 删除当前队列，自动取消订单任务执行完成
            } else {
                Log::info('发送第 '.$this->attempts().' 条催单信息...');
                Log::info('队列延迟10分钟...');
                $this->release(now()->addMinutes(10)); // 当前队列延期10s执行
            }
        } else {
            Log::info('用户已支付，直接完成当前队列...');
        }
    }

    // 自定义处理队列异常信息
    public function failed(\Exception $e)
    {
        Log::error(static::class.' 队列异常信息：'.$e->getMessage());
    }
}
