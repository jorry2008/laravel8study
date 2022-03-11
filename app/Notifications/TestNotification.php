<?php

namespace App\Notifications;

use App\Channels\WecahtChannel;
use App\Mail\TestMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TestNotification extends Notification implements ShouldQueue
{
    use Queueable;

//    public $connection = 'redis';
//    public $queue = 'default';
//    public $locale = 'us';

    public function __construct()
    {
        //

    }

    /**
     * 支持多个频道，同时推送
     * $notifiable 表示消息发送者实例 App\Models\NotifyOrder
     */
    public function via($notifiable)
    {
        // 这里可根据业务需求有选择性的给指定的频道发送消息
        // return $notifiable->user->disable_mail ? ['database'] : ['database', 'mail'];
//        return ['mail', WecahtChannel::class];
        return ['database', 'mail'];
    }

    /**
     * 还支持 toMail、toBroadcast
     * 将数组参数被转化为 json 并存储到 notifications 数据表中的 data 字段中
     * 注意：实现了 toDatabase() 并携带参数了，此时 toArray() 无效
     */
    public function toDatabase()
    {
        return new DatabaseMessage(['content' => 'abcd']); // 这里是直接入库的数据，最后 toString() 入库了，与直接返回数组等效
    }

    public function toMail($notifiable)
    {
        // 注意，这里接受两种类型，一个是实现 Mailtable，一个是 MailMessage
        // 其中，Mailtable 与正常邮件发送类的用法一样，并且同样支持预览，以下这种模式不支持 ->to() 方法，发送目标人在 Notiable 中添加方法（就是发送消息的模型）
        return (new MailMessage)
//            ->mailer('smtp')
//            ->from('jorry@xinma.cloud', 'jorry@xinma.cloud')
//            ->view(
//                'emails.name', []
//            )
//            ->markdown('notifications::email', [])
//            ->template()
//            ->theme()
//            ->error()
            ->success()
            ->greeting('Hello!')
            ->subject('新用户注册')
            ->line('你好我是测试消息通知')
            ->action('查看', url('/'))
            ->line('感谢您的支持');

        // 基于 Mailtable，这个方式在消息通知场景不实用
//        $message = new TestMail();
//        $message->onConnection('database'); // 注意：onConnection，onQueue 两个方法不能在 Mailable 中的 build() 构建
//        $message->onQueue('email');
//        $message->to([['name' => 'jorry', 'email' => '980522557@qq.com'],]);
//        return $message;
    }

    /**
     * 自定义微信消息通知
     */
    public function toWechat()
    {
        // 发送微信消息
    }

    /**
     * 一个消息，多个频道，为每个频道分别设置不同的队列名称
     */
    public function viaQueues()
    {
        return [
            'database' => 'queue_database',
            'mail' => 'queue_mail',
        ];
    }

    /**
     * 为每个频道分别设置延迟时间
     */
    public function delay()
    {
        return [
            'database' => now()->addMinutes(10),
            'mail' => now()->addMinutes(5),
        ];
    }

    /**
     * 写入自己的业务逻辑，判断当前消息是否可以满足可发送条件
     */
    public function shouldSend($notifiable, $channel)
    {
        return true;
    }

    /**
     * 中间件配置
     */
    public function middleware()
    {
        return [];
    }

    /**
     * 同上，以 json 的方式存储在 notifications 表中的 data 字段
     * 优先级最低
     */
    public function toArray($notifiable)
    {
        return [
            'invoice_id' => '单剧id',
            'amount' => 1800,
        ];
    }
}
