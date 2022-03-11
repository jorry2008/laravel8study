<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

class PublicMessageEvent implements ShouldBroadcast // ShouldBroadcastNow 立即广播
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * https://learnku.com/docs/laravel/9.x/broadcasting/12223#b23c1d
     * 非常重要，如果事件触发正好有业务的执行事务中时，这个参数就是表示，在事务执行完成后，队列才可以被执行，
     * 从而避免事务失败或队列执行前置的问题。
     */
    public $afterCommit = true;

    /**
     * 消息内容
     * @var string
     */
    public $message;

    /**
     * Create a new event instance.
     *
     * @param string $message
     */
    public function __construct(string $message)
    {
        $this->message = $message;
    }

    /**
     * 发送给指定通道
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        Log::info($this->socket);

        return new Channel('push'); // 支持数组，同时发给多个频道
    }

    /**
     * Laravel 默认会使用事件的类名作为广播名称来广播事件，自定义：
     */
    public function broadcastAs()
    {
        return 'push.message';
    }

    /**
     * 想更细粒度地控制广播数据:
     */
    public function broadcastWith()
    {
        return ['message' => $this->message, 'status' => 'okok'];
    }

    /**
     * 队列的执行条件
     * @return bool
     */
    public function broadcastWhen()
    {
        // return $this->order->value > 100;
        return true;
    }
}
