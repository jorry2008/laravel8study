<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_断言登录用户可以访问到邮箱验邮箱激活页面()
    {
        $user = User::factory()->create([
            'name' => 'EmailVerificationTest'.rand(0, 9999), // 自动生成数据的技巧
            'email_verified_at' => null,
        ]);

        // $this->actingAs($user) 设置指定用户为当前应用的登录用户，这也太强了吧
        $response = $this->actingAs($user)->get('/verify-email');

        $response->assertStatus(200);
    }

    public function test_断言用户验证了自己的邮箱()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        Event::fake();
        // 所有模拟都是假的，这里表示不会在以下代码中触发 Verified::class 事件
        // 注意：调用 Event::fake() 后不会执行事件监听。所以，如果你的测试用到了依赖于事件的模型工厂，例如，在模型的 creating 事件中创建 UUID ，那么你应该在调用 Event::fake() 之前 使用模型工厂创建数据。
        // 也就是说，在有事件处理的代码中，必须要脱离事件进行测试。
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)] // hash 使用 email 为原始凭证，因为此测试过程就是为了测试邮件验证的，如果需要验证其它的内容，原始凭证也应该跟着变
        );

        $response = $this->actingAs($user)->get($verificationUrl); // 登录用户请求验证邮箱地址操作

        Event::assertDispatched(Verified::class); // 仅仅是正常的事件分发，模拟触发相关监听程序

//        Log::info('url测试', [$verificationUrl]);

        // $user->fresh()，因为验证过后用户验证时间更新了，当前 $user 数据需要同步更新
        $this->assertTrue($user->fresh()->hasVerifiedEmail()); // 断言 true
        $response->assertRedirect(RouteServiceProvider::HOME.'?verified=1');
    }

    public function test_断言用户未验证自己的邮件()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $verificationUrl = URL::temporarySignedRoute( // 路由 verification.verify 对应 verify-email/{id}/{hash}
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1('wrong-email')] // 使用了错误的hash
        );

        $this->actingAs($user)->get($verificationUrl);

        $this->assertFalse($user->fresh()->hasVerifiedEmail()); // 断言 false
    }

    // 可以看到这三个断言，重点测试的就是 hash 码的生成与比对验证动作
    // 写测试用例的技巧：每个测试用例都只对一个目标进行正反面测试，才算基本保证达到测试目的

    // 在写功能性的测试用例时，其主要还是在写与业务逻辑之间的交互，真正用到的断言其实不多
}
