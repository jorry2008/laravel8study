<?php

namespace App\Http\Controllers;

use App\Mail\TestMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class TestQueueController extends Controller
{
//    public $middleware = [''];

    public function testEmail()
    {
        // 创建 markdown 邮件的方式：php artisan make:mail TestMail --markdown=emails.test_mail
        // 非队列的那些 html 或 plain、raw 的方式发送，可以直接查看手册，比较简单。
        // Mail::mailer('smtp')->send((new TestMail())->to('980522557@qq.com', 'jorry'));

        // 第一种，隐性队列发送，邮件实现了ShouldQueue将自动以队列的方式发信息 // 正常的发送，也会丢进指定队列
        // Mail::mailer('smtp')->send((new TestMail())->onConnection('database')->onQueue('email'));

        // 第二种，以显性队列的方式发送邮件
        $message = new TestMail();
        $message->onConnection('database'); // 注意：onConnection，onQueue 两个方法不能在 Mailable 中的 build() 构建
        $message->onQueue('email');
//        $message->locale('en_US');
//        $message->delay(now()->addSeconds(10));
//        $message->later(10);

        // 指定多用户发送
        Mail::mailer('smtp')->to([
            [
                'name' => 'jorry2008',
                'email' => '980522557@qq.com'
            ],
        ])->send($message); // ->queue($message)

        // 用户对方发送
//        Mail::mailer('smtp')->to(Auth::user())->send($message); // ->queue($message)
        if (count(Mail::failures()) < 1) {
            echo '发送成功...';
        }

        echo '测试队列邮箱 -> '.now()->toDateTimeString();
    }
}
