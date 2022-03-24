<?php

namespace Tests\Feature;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class HttpTest extends TestCase
{
    use RefreshDatabase;

    // 就是测试一个链接在某条件下是否正常访问
    public function test_user_with_x_header_post_and_name()
    {
        $response = $this->withHeaders([
            'X-Header' => 'Value',
        ])->post('test/user', ['name' => 'Sally']);

        $response->assertStatus(200); // 响应为 TestResponse 对象
    }

    /**
     *  就是测试cookie是否可用
     * @group example // 这里是分组！！！！不需要在 xml 中操作
     */
    public function test_cookie_with_name_color()
    {
        $response = $this->withCookie('color', 'blue')->get('test/cookie');

        $response->assertCookie('color', 'blue');

        $response = $this->withCookies([
            'color' => 'blue5',
            'name' => 'Taylor',
        ])->get('test/cookie');

        $response->assertCookie('color', 'blue5')->assertCookie('name', 'Taylor');
    }

    // 就是测试果断json内容是否达到要求（这个测试与请求响应关系不大，除了 assertStatus）
    public function test_asserting_a_json_paths_value()
    {
        $response = $this->json('POST', 'test/json', ['name' => 'Sally']);

        $response
            ->assertStatus(201)
            ->assertJsonPath('team.owner.name', 'Darian');

        // 只能说，太牛批了，直接从响应内容反推使用的是什么视图
//        $response->assertViewHas($key, $value = null);
//        $response->assertViewMissing($key); // 断言视图缺少指定的键
//        $response->assertViewIs($value); // 断言当前路由返回的的视图是给定的视图
//        $response->assertViewHasAll([
//            'name',
//            'email',
//        ]);
//        $response->assertViewHasAll([
//            'name' => 'Taylor Otwell',
//            'email' => 'taylor@example.com,',
//        ]);

    }

    // 就是测试基于 http 的文件上传：测试指定上传驱动是否可用，还测试指定上传路径是否正确
    public function test_upload_file_can_be_uploaded()
    {
        // Storage::fake('需要模拟的驱动名称');
        Storage::fake('public'); // public 就是需要测试的驱动，测试的就是当前有的，没有的测试也没用

        $file = UploadedFile::fake()->image('avatar.jpg'); // 这里使用 fake 创建虚拟文件，这个文件是真实存在的，牛

        $response = $this->post('test/file', [ // 将文件对象发送至服务器
            'test_file' => $file,
        ]);

        // $this->assertEquals('avatars/' . $file->hashName(), Upload::latest()->first()->file); // 测试两个字符串相同
//        Storage::disk('public')->assertMissing('missing.jpg'); // 测试这个文件一定不存在

        // avatars 目录，就是文件上传的目录，测试的就是这个
        Storage::disk('public')->assertExists('avatars/' . $file->hashName()); // 使用 Storage 直接检测文件是否存在，这也太牛了吧
    }

    // 就是测试文本文档中指定的字符串是否符合要求，包含要求、排序要求、转义要求
    public function test_view_template_can_be_rendered()
    {
        $view = $this->view('test_file', ['name' => '<h3>Taylor</h3>']); // 值会被转义

        // $view->assertSee('Taylor'); // 模板中的变量值是否为 Taylor，在这里其实测试的是：test_file 模板是否能正常渲染变量 $name

        $view->assertSee('<h3>Taylor</h3>', true); // 断言给定的字符串包含在 响应/模板 中
        $view->assertSeeInOrder(['Submit', '<h3>Taylor</h3>'], true); // 断言给定的字符串按顺序包含在 响应/模板 中
        $view->assertSeeText('<h3>Taylor</h3>', true); // 断言给定字符串包含在响应文本中(它会将 响应/模板 内容中的所有 html 用 strip_tags() 全部清除)。
//        $view->assertSeeTextInOrder(); //
//        $view->assertDontSee(); //
//        $view->assertDontSeeText(); //
        // 注意，所有断言没有固定必须是哪个对象返回，主要看对象是否支持断言，比如：->assertSee() 被 TestView、TestResponse、TestComponent 同时支持。
        // 因此，断言是成体系的独立的一个概念，与 Laravel 框架没有关系，只是框架中的某些组件实现了而已，注意，断言是需要自己实现的，退一万步，断言更像一种测试规则或标准。
    }

    // 以下是几个文本检测，只是场景不同
//    public function test_render_view_with_errors()
//    {
        /*
        // 共享错误
        $view = $this->withViewErrors([
            'name' => ['Please provide a valid name.'] // 此 name 将会触发模板中的错误提示，进而将提示文本写入到模板文本
        ])->view('form');

        $view->assertSee('Please provide a valid name.');

        // 渲染模板 & 组件
        $view = $this->blade(
            '<x-component :name="$name" />', // 直接调用组件
            ['name' => 'Taylor']
        );

        $view->assertSee('Taylor');

        // 组件2
        $view = $this->component(Profile::class, ['name' => 'Taylor']);

        $view->assertSee('Taylor');
        */
//    }

    public function test_登录身份验证测试()
    {
        $this->assertGuest(); // 当前为游客

        $user = User::factory()->create(); // 在测试代码中操作数据库没有太多意义！！！

        // 登录操作
        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]); // 整个请求会基于 cookie 自动处于验证状态，所以下面的所有身份认证相关的方法，直接使用就可以了，不需要 $response
        $this->assertAuthenticated(); // 已经登录
        $this->assertAuthenticatedAs($user); // 或直接指定user

//        Log::info($response->headers);
//        Log::info($response->content());
        // 断言，response 是否跳转到指定位置
        $response->assertRedirect(RouteServiceProvider::HOME); // http://laravel8study.cc/dashboard

//        $response->assertSuccessful(); // 断言响应一个成功的状态码 (>= 200 且 < 300)
//        $response->assertUnauthorized();// 断言一个未认证的状态码 (401)
    }
}
