<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ReportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // 个性化重试次数设置
    public $tries = 5;

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
        // 业务异常
        throw new \Exception('业务异常了');


        //
//        $this->fail(new \Exception('这是一个主任务的失败测试'));
    }

    public function failed(\Exception $e)
    {
        Log::info('打个日志，记录一下异常：'.$e->getMessage());
    }
}
