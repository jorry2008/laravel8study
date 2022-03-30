<?php

namespace App\Jobs\Request;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ThirdRequestJob implements ShouldQueue
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
        return now()->addMinutes(1);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            sleep(3); // 模拟执行 3 秒
            $response = Http::timeout(5)->get('https://baidu.com');
            if (!$response->failed()) {
                Log::info('请求成功，结束当前任务...');
            }
        } catch(\Exception $e) {
            $delayTime = 5 * $this->attempts();
            Log::error('第 '.$this->attempts().' 次执行请求失败，延期 '.$delayTime.'s...');
            $this->release($delayTime); // 跳跃式延期

            // 方案二：保持原有的跳跃式延期执行，设置指定的重试次数即可，超过次数不再延期
//            if ($this->attempts() > 3) {
//                // 不延期，直接结束当前任务
//                Log::error(new \Exception($this->attempts().' 次请求失败，任务结束...'));
//            } else {
//                $this->release(15 * $this->attempts()); // 跳跃式延期
//            }
        }
    }

    // 自定义处理队列异常信息
    public function failed(\Exception $e)
    {
        Log::error(static::class.' 队列异常信息：'.$e->getMessage());
    }
}
