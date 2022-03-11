<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;

class WecahtChannel
{
    /**
     * @param  mixed  $notifiable
     * @param  Notification  $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        // 这里就是写微信通知发送逻辑
        $notifiable->toWechat($notification);
    }
}
