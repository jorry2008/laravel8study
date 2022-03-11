<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * 邮件或队列之间的秘密：
 * 一、web请求，走的是web系统的执行，console走控制台执行，两条路径的运行环境完全不同，因此在发件队列中无法获取web系统中创建的config配置量，同理其它无法共用的资源了也是因为这个问题导致的。
 *      当采用队列操作时，永远要保持中立，即job的所有数据与原web已有的数据无关。
 * 二、smtp协议告诉我们，所有在 DATA 中的数据，都是可以自定义的，所以每个服务商给我们提供的服务定制都不相同，请注意这个问题。
 * 三、实现此接口 ShouldQueue 的邮件，默认就是队列模式！
 */

class TestMail extends Mailable implements ShouldQueue // 实现队列接口
{
    use Queueable, SerializesModels; // use Queueable 队列可配置参数

    /**
     * 这里主要负责传递一些参数过来，使用类型提示会更方便
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.这个方法的实现最为关键
     */
    public function build()
    {
        $this->onQueue('email')->markdown('emails.test_mail', [])
            //->to()
//            ->from('jorry@xinma.cloud', '世标xxxx') // 邮件来源，通常服务商把代发关闭了，必须保证From来源和发信账号保持一致，否则验证不通过（亚马逊开放了些限制）
//            ->cc([['name' => 'abcd', 'email' => 'xiayouqiao2008@163.com']]) // 抄送
//            ->bcc('xiayouqiao2008@163.com') // 密送
//            ->replyTo('xxxxxx@qq.com', '被回复人') // 自动回复，即点击回复时，邮件客户端自动要发送的目标邮件地址（有些服务商或客户端将此屏蔽了）
            ->subject(__('Test Queue Email')); // 标题

        // 抄送
        // 这里非常要注意，异步处理时，artisan并没有走web系统，只是走的console控制台，所以根本不存在config('setting')配置！！！
//        $this->cc([
//            [
//                'name' => '',
//                'email' => '',
//            ], [
//                'name' => '',
//                'email' => '',
//            ]
//        ]);

        return $this;
    }
}
