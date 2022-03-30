<?php

namespace App\Jobs\Meetting;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

// 将所有退款用户订单
// 逻辑简单，本地执行，速度极快，不考虑极端情况
// 这一步，其实可以直接写到业务逻辑 Model 中，不需要队列处理机制
class CancelMeetJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $userOrders;

    public function __construct(Collection $userOrders)
    {
        $this->userOrders = $userOrders;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (!$this->userOrders->isEmpty()) {
            foreach ($this->userOrders as $userOrder) {
                RefundJob::dispatch($userOrder);
            }
        }
    }
}
