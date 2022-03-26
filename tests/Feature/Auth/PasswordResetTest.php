<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_断言找回密码表单页面可访问()
    {
        $response = $this->get('/forgot-password');

        $response->assertStatus(200);
    }

    public function test_断言找回密码提交动作可访问()
    {
        Notification::fake(); // 模拟，只走流程不会触发

        $user = User::factory()->create();

        $this->post('/forgot-password', ['email' => $user->email]);

        Notification::assertSentTo($user, ResetPassword::class); // 模拟触发重置密码通知
    }

    public function test_断言用户提交重置密码请求后，并从通知中点击重置密码链接访问成功()
    {
        Notification::fake(); // 模拟，只走流程不会触发

        $user = User::factory()->create();

        $this->post('/forgot-password', ['email' => $user->email]);

        Notification::assertSentTo($user, ResetPassword::class, function ($notification) { // Notification::assertSentTo() 专门用来测试消息通知的
            $response = $this->get('/reset-password/'.$notification->token);

            $response->assertStatus(200);

            return true;
        });
    }

    public function test_断言用户提交重置密码请求后，并从通知中点击重置密码链接，然后再填充新密码做提交动作成功()
    {
        Notification::fake();

        $user = User::factory()->create();

        $this->post('/forgot-password', ['email' => $user->email]); // 一次提交，获得重置消息，消息中包含加密链接

        // 通知消息基于回调函数进行测试
        Notification::assertSentTo($user, ResetPassword::class, function (\Illuminate\Notifications\Notification $notification) use ($user) {

            // 这部分就是在实现消息通知之后，用户进一步提交新密码的表单
            $response = $this->post('/reset-password', [ // 通过加密链接展示重置密码表单，提交新密码即可
                'token' => $notification->token,
                'email' => $user->email,
                'password' => 'password',
                'password_confirmation' => 'password',
            ]);

            $response->assertSessionHasNoErrors();

            return true;
        });
    }
}

// 小结：密码重置功能，共计两次表单展示 + 两次表单提交，共 4 次测试，这是一个典型的交互测试全流程

/**
 * 小结：
 * 事件如何测试触发成功？
 * 消息通知如何触发成功？
 * 如何判断监听成功？
 * 如何判断队列是走通的？
 * ......
 */
