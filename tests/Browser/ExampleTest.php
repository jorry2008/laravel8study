<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ExampleTest extends DuskTestCase
{
    /**
     * A basic browser test example.
     *
     * @return void
     */
    public function testBasicExample()
    {
        $this->browse(function (Browser $browser) {
            // 打开首页
            $browser->visit('/')
                    ->assertSee('Laravel');

            // 最大化窗口
            $browser->maximize();

            // 创建虚拟账号
            $user = User::factory()->create(); // 密码为 password

            // 点击进入登录页面
//            $browser->click('#test-login');
            $browser->click('@login-button');

            // 填写账号
//            $browser->keys('#email', ['{shift}', 'jorry'], '_jorry'); // 大小写切换
//            $browser->keys('#password','password');

            $browser->visit('/login')
                ->type('email', $user->email)
                ->type('password', 'password')
                ->press('Log in')
                ->assertPathIs('/dashboard'); // 跳转到首页

        });
    }
}
