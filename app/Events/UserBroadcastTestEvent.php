<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserBroadcastTestEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    // 对于广播,直接将 Event 事件当成 Job 来配置
//    public $connection = 'redis';
//    public $queue = 'broadcast';
//    public $afterCommit = true;

    public $user;

    public $message;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, string $message)
    {
        $this->user = $user;
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
//        return new PrivateChannel('user.'.$this->user->id); // 广播是基于事件 Event
        return new Channel('MyChannel');
    }

    /**
     * 事件的广播名称。
     * @return string
     */
    public function broadcastAs()
    {
        return 'MyChannel.event';
    }

    /**
     * 指定广播数据。
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'message' => $this->message
        ];
    }
}
