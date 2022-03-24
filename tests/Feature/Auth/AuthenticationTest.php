<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_断言（坚持认为）登录页面可正常渲染()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_断言用户可登录并跳转到预期的地址()
    {
        $user = User::factory()->create();

        // post 请求不需要考虑 csrf_token，在测试环境，此验证自动屏蔽了
        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    public function test_断言非认证用户无法登录()
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password', // 使用了一个错误密码，预期登录不成功
        ]);

        $this->assertGuest();
    }

    // 小结：测试一个登录功能一共三个步骤，先测试登录页面是否正常、再测试正确的用户可登录、最后测试不正确的用户不可登录
}
