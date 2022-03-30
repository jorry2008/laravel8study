<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * 需求：对外接口访问，接口自身有速率限制，考虑网络稳定性，允许异常失败，核心功能就是要尽最大可能的实现对接口的访问。
 *
 * 实现：在12小时以内，将访问频次控制在api限制的频次范围以内，允许多次访问多次失败，直到能成功访问到接口并获取数据！
 * 使用场景，比如：定时任务一天一次，获取当天的付费菜谱api，如果第三方接口挂掉会自动发信息给开发人员。
 */
class LimitApiRateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels; // InteractsWithQueue 使队列具备交互能力

    public $tries = 0; // 不限重试次数，最终的失败由任务执行超时来决定

    // 队列任务执行过程中抛出的未处理异常，最大允许3次，哪怕时间未到，也会失败
    public $maxExceptions = 3; // 允许3次异常

    // 任务的超时时间
    public function retryUntil()
    {
        return now()->addHours(12); // 接口重试时长保持在12小时
    }

    public function __construct()
    {
        //
    }

    /**
     * 以下思路由两部分组成：
     * 1.正常模式：利用延时，在访问失败或者触发api限制时，延时到下一个合适的时间点即可。
     * 2.并发模式：考虑并发和幂等性，利用缓存时间来判断，对当前的进程做精准的延时，对同一个任务进行执行的多个进程形成了排它处理。
     */
    public function handle()
    {
        // 有时间限制缓存，表示当前不允许访问接口，否则出现 429【重点：这里是在并发场景下的一个缓存判断再延时机制，类似锁的功能，实现幂等】
        if ($timestamp = Cache::get('api-limit')) {
            return $this->release($timestamp - time()); // 正好延时到可以访问的时间（正常情况队列一定会被推迟）
        }

        $response = Http::acceptJson()
            ->timeout(10)
            ->withToken('...')
            ->get('https://...');

        // 通常只会在第一次触发429，更多情况触发的是响应失败
        if ($response->failed() && $response->status() == 429) {
            $secendsRemaining = $response->header('Retry-After'); // 现实生活中，这个标记并不多见，使用固定值就好了

            // 为并发而实现的机制
            Cache::put(
                'api-limit',
                now()->addSeconds($secendsRemaining)->timestamp, // 指定延时的时间
                $secendsRemaining // 延时的有效期，正好也是接口恰好可访问的时间，失效，同样
            );

            return $this->release($secendsRemaining); // 如果只有一个进程work，那么此仅一句延期就可以了，但不排除并发
        }

        // ... 业务逻辑...

    }

    public function failed(\Exception $e)
    {
        Log::info('TestJob 打个日志，记录一下异常：'.$e->getMessage());
    }
}
