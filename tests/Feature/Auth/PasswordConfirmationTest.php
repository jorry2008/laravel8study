<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class PasswordConfirmationTest extends TestCase
{
    use RefreshDatabase; // 可调用 migrate:fresh，清空当前数据库

    public function test_断言更新密码页面可访问()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/confirm-password');

        $response->assertStatus(200);
    }

    public function test_断言登录用户修改密码成功()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/confirm-password', [ // 当前登录用户 post 密码为：password
            'password' => 'password',
        ]);

        $response->assertRedirect(); // 断言响应中包含跳转
        $response->assertSessionHasNoErrors(); // 断言响应数据中没有验证报错
    }

    public function test_断言未登录成功的用户无法修改密码()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/confirm-password', [
            'password' => 'wrong-password',
        ]);

//        $response->dd();
//        $response->ddHeaders();
//        $response->ddSession(); // 将 session 打印出来

//        Log::info($response->headers);
//        Log::info($response->content());

        $response->assertSessionHasErrors(); // 断言响应数据中包含有错误信息
    }
}
