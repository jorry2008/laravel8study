<?php

namespace App\Jobs\Meetting;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RefundJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;

    public $timeout = 1*3600 + 5*60; // 1小时5分钟

    public function retryUntil()
    {
        return now()->addHours(1); // 一个小时的生命周期
    }

    protected $userOrder;

    public function __construct(UserOrder $userOrder)
    {
        //
    }

    public function handle()
    {
        // 考虑两个问题：
        // 一、实时获取订单退款状态，使其幂等。
        // 二、状态为未退款，并非真正的未退款成功，可能是退款成功了，但回调失败了，真实状态必须以网关结果为准。
        // 三、多个进程/线程处理同一个任务时，需要排它处理，这个最难实现。如果两个队列处理器进程同时获取到这两个执行同一笔退款的任务，可能会存在同时发送 HTTP 请求并进行退款的操作，进而出现并发安全问题。

        $userOrder = $this->userOrder;
        if (!$userOrder->isRefund) { // 只处理未支付，这里就解决了幂等性的问题

            // 原子锁，必须要使用锁释放，比如：10s，因为锁有奔溃的风险
            Cache::lock('refund'.$userOrder->id, 10)
                ->get(function() use ($userOrder) {
                    // 获取支付回调信息
                    // 失败重试，最多三次请求支付网关，三次都失败，超出次数任务自动异常并失败
                    $response = Http::timeout(5)->post([
                        'xxx' => 'xxxx'
                    ]);

                    if ($response->failed()) {
                        $this->release(10*60*$this->attempts()); // 逐次延迟执行时间
                    } else {
                        // 如果回调信息显示为已经退款，这里就回写退款状态，并发送信息
                        if ($response->isPayxxx) {
                            // 回写状态逻辑
                            // 发送消息
                            MessageJob::dispatch($this->userOrder);
                        } else {

                            // 请求支付网关，发起退款

                            // 失败重试

                            // 成功回写状态，并发送消息
                            MessageJob::dispatch($this->userOrder);
                        }
                    }
                });
        } else {
            // nothing，完成当前任务
        }

        // 方案二：使用批处理，解决一次redis连接，只能处理一条任务的情况。
        // 此方案更加合适直观，退款会议上还能监控到整个批量队列的状态
//        Bus::batch()->dispatch($jobs);
        // 属于 laravel8 中的内容
        // https://laravelacademy.org/post/22256 批量队列使用
        // https://laravelacademy.org/post/22258 批量队列监控
        /*
        // 批处理队列监控
        $batch = Bus::findBatch($batchId);
        return [
            'progress' => $batch->progress().'%',
            'remaining_refunds' => $batch->pendingJobs,
            'has_failures' => $batch->hasFailures(),
            'is_cancelled' => $batch->canceled(),
        ];

        // 成功发通知、失败发提醒、完成写日志
        $jobs = $userOrders->map(function($meet) {
            return new RefundMeetJob($meet);
        });
        Bus::batch($jobs)
            ->allowFailures()
            ->then(function() {
                // 直接发通知，或通知入队列
            })
            ->catch(function() {
                // 直接发通知，或通知入队列
            })
            ->finally(function() {
                // 写日志
            })
            ->dispatch();
        */
    }

    // 自定义处理队列异常信息
    public function failed(\Exception $e)
    {
        // 这个程序异常了，必须将消息推送给开发人员，可以先用稳定的大的云供应商，不放心的话，可以考虑多消息推送。
        Log::error(static::class.' 队列异常信息：'.$e->getMessage());
    }
}
