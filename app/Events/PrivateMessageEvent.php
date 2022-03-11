<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PrivateMessageEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    /**
     * 消息内容
     *
     * @var string
     */
    public $user;
    public $message;

    /**
     * Create a new event instance.
     *
     * @param User $user
     * @param string $message
     */
    public function __construct(User $user, string $message)
    {
        $this->user = $user;
        $this->message = $message;
    }

    /**
     * 私有频道
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('wechat.' . $this->user->id);
    }

    // 重新定义事件名
    public function broadcastAs()
    {
        return 'push.message';
    }

    // 想更细粒度地控制广播数据:
    public function broadcastWith()
    {
        return [
            'user' => $this->user,
            'message' => $this->message,
            'status' => 'okok',
        ];
    }
}
