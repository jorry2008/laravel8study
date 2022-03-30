<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Events\UserTestEvent;
use App\Events\UserBroadcastTestEvent;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Http;
use Illuminate\Cache\RateLimiter;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', function (\Illuminate\Http\Response $response) {

    if ($user = Auth::user()) {
//        UserTestEvent::dispatch($user);
//        Event::dispatch($user);

        // 事件队列化
//        event(new UserTestEvent($user));

        // 事件广播
//        $message = '我叫赵六';
//        event(new UserBroadcastTestEvent($user, $message));
//        broadcast(new UserBroadcastTestEvent($user, $message));
//        UserBroadcastTestEvent::dispatch($user, $message);

        // 学院君
//        event(new \App\Events\UserSignedUp($user));
    }

    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';


// 公有频道
Route::get('/push/{message}', function ($message) {
    broadcast(new \App\Events\PublicMessageEvent($message));
    return view('success');
});

Route::get('/echo', function () {
    return view('echo');
});

// 私有频道（后台异步生成报表，异步更新进度，即自己给自己发消息）
Route::get('/privatePush/{message}', function ($message) {
    if (Auth::guest()) {
        return view('notlogin');
    } else {
        broadcast(new \App\Events\PrivateMessageEvent(Auth::user(), $message));

        // broadcast(new PrivateMessageEvent(Auth::user(), $message))->via('channel2'); // 同一个事件，可以推送到临时指定的频道
        return view('success');
    }
});

// 存在频道
Route::get('/presencePush/{message}', function ($message) {
    if (Auth::guest()) {
        return view('notlogin');
    } else {
        broadcast(new \App\Events\PresenceMessageEvent(Auth::user(), $message, 100))->toOthers();

        // 比如：当前用户加入一个群聊组时，这个加入群聊通知信息应该发送给所有此群的其它用户
//        broadcast(new \App\Events\PresenceMessageEvent(Auth::user(), $message, 100))->toOthers(); // 事件触发的当前用户，将事件广播给其它订阅过此事件的所有用户
        return view('success');
    }
});

// 模型广播
Route::get('/push2/model', function () {
    if (Auth::guest()) {
        return view('notlogin');
    } else {

        // 修改模型即可
        $orderModel = \App\Models\Order::query()->find(19);
        $orderModel->update(['name' => 'abc'.rand()]);

        return view('success');
    }
});

// 通知消息
Route::get('notify', function () {
    $orderModel = \App\Models\NotifyOrder::query()->first();
    $notify = new \App\Notifications\TestNotification();
    $notify->onConnection('database')->onQueue('default')->afterCommit(); // ->locale();
    $orderModel->notify($notify);
    // 同
//    \Illuminate\Support\Facades\Notification::send($orderModel, $notify);
//    \Illuminate\Support\Facades\Notification::sendNow($orderModel, $notify); // 强制跳过队列，立即执行发送

    // 匿名消息通知
//    \Illuminate\Support\Facades\Notification::route('mail', 'taylor@example.com')->notify(new \App\Notifications\TestNotification());

    return view('success');
});

// 一、普通队列说明
Route::get('test/job', ['App\Http\Controllers\TestQueueController', 'testJob']);

// 二、email队列发送说明
Route::get('test/mail', ['App\Http\Controllers\TestQueueController', 'testEmail']);

// 三、报表队列说明
Route::get('test/report', ['App\Http\Controllers\TestQueueController', 'TestReport']);

// 四、自动取消已放弃订单任务
Route::get('test/order', ['App\Http\Controllers\TestQueueController', 'TestOrder']);


// 发送邮件
Route::get('test/mail', ['App\Http\Controllers\TestQueueController', 'testEmail']);
Route::get('/mailable', function () { // 预览功能极其重要
    return new \App\Mail\TestMail();
});

Route::get('/notification-mailable', function () { // 预览消息通知默认的邮件信息类
    return (new \Illuminate\Notifications\Messages\MailMessage())
        ->subject('新用户注册')
        ->line('你好我是测试消息通知')
        ->action('查看', url('/'))
        ->line('感谢您的支持');
});

// 限流测试
Route::get('limiter', function () {
    // 在指定的时间30s内，允许的执行5次，正常不超出时，执行回调，并返回 $executed 为 true
    $executed = RateLimiter::attempt('cache-key', 5, function() {}, 60);
    if (RateLimiter::tooManyAttempts('cache-key', 5)) { // 判断 RateLimiter::attempt() 尝试是否已经超出了，即太多尝试次数
        echo '已经超出尝试数次...<br>';
    } else { // 还未超出，允许访问
        echo '未超出尝试次数...<br>';
    }
    echo '还剩下多少可尝试次数：'.RateLimiter::remaining('cache-key', $perMinute = 5).'<br>';
    echo '本次限制还剩下多少秒：'.RateLimiter::availableIn('cache-key');
    RateLimiter::hit('cache-key'); // 击中一次，将剩余尝试次数 +1（即：可尝试次数少一个）
    // RateLimiter::clear('cache-key'); // 清除，恢复如初

    return view('success');
});

// HTTP client
Route::get('http-client', function () {

    $response  = Http::get('http://laravel8study.cc/echo');
    // 具体使用请参考手册，这里只说重点

    // 1.输出内容
//    $response = \Illuminate\Support\Facades\Http::get('http://laravel8study.cc/echo');
//    $response->body() // string;
//    $response->json() // array|mixed;
//    $response->collect() // Illuminate\Support\Collection;
//    $response->status() // int;
//    $response->ok() // bool;
//    $response->successful() // bool;
//    $response->failed() // bool;
//    $response->serverError() // bool;
//    $response->clientError() // bool;
//    $response->header('X-RateLimit-Remaining') // string;
//    $response->headers() // array;
    // 请求数据、请求头、认证、超时、重试、错误处理

    Http::withOptions([
        'debug' => true,
    ])->get('');

    // 2.验证相关
    // 直接访问 https 时，可以手动设置路过，也可以手动添加 pem 证书
    // 证书下载地址：https://curl.se/docs/caextract.html    https://curl.se/ca/cacert.pem
//    $response = Http::withOptions([
//        'verify' => resource_path('ca/cacert.pem'),
////        'debug' => true,
//    ])->get('https://baidu.com/');

//    dd($response->headers());

    // 3.模拟测试【这是在laravel中最重要的特性】
    // 使用流程是：先 fake() 模拟，再实测，最后看结果。
    // 重点：它不需要走正式的外网就可以完成模拟测试，就是预先给定结果再执行“请求”的意思。

    // 模拟响应
//    Http::fake();
//    $response = Http::withoutVerifying()->get('https://baidu.com/');
//    dd($response->headers());

    // 模拟指定地址的响应
//    Http::fake([
//        'github.com/*' => Http::response(['foo' => 'bar'], 200, ['Headers']),
//        'baidu.com/*' => Http::response('Hello World', 200, ['这是响应200的结果....']),
//    ]);
//    // 前面有 fake() 已执行，此时不需要真正访问网络，非常有利于在保证响应正确的情况下进一步完成响应测试
//    $response = Http::withoutVerifying()->get('https://baidu.com/');
//    $response = Http::withoutVerifying()->get('https://github.com/');
//    dd($response->headers(), $response->body());

    // 伪造响应队列
//    Http::fake([
//        // 模拟发往 github.com 的请求的一系列响应
//        'github.com/*' => Http::sequence()
//            ->push('Hello World', 200)
//            ->push(['foo' => 'bar'], 200)
//            ->pushStatus(404),
//    ]);
//    $response = Http::withoutVerifying()->get('https://github.com/'); // 当前返回第一个push
//    var_dump($response->headers());
//    var_dump($response->body());
//    echo '<br />';
//    $response = Http::withoutVerifying()->get('https://github.com/'); // 当前返回第二个push
//    var_dump($response->headers());
//    var_dump($response->body());
//    echo '<br />';
//    $response = Http::withoutVerifying()->get('https://github.com/'); // 当前返回第三个push
//    var_dump($response->headers());
//    var_dump($response->body());

    // 模拟回调
//    Http::fake(function ($request) {
//         return Http::response('当请求完成后，会执行这个操作', 200);
//    });
//    $response = Http::withOptions([
//        'verify' => resource_path('ca/cacert.pem'),
//    ])->get('https://github.com/');
//    var_dump($response->headers());
//    var_dump($response->body());

    // 注入请求，进一步检查响应的正确性
//    Http::fake();
//    Http::withHeaders([
//        'X-First' => 'foo',
//    ])->post('http://example.com/users', [
//        'name' => 'Taylor',
//        'role' => 'Developer',
//    ]);
//    Http::assertSent(function (\Illuminate\Http\Client\Request $request) {
//        return $request->hasHeader('X-First', 'foo') &&
//            $request->url() == 'http://example.com/users' &&
//            $request['name'] == 'Taylor' &&
//            $request['role'] == 'Developer';
//    });
//    Http::assertNotSent(function (\Illuminate\Http\Client\Request $request) {
//        return $request->url() === 'http://example.com/posts'; // 错误，则对，断言
//    });
});


// Mix测试
Route::get('mix', function () {
    return view('mix');
});

// tailwindcss 测试
Route::get('tailwind', function () {
    return view('tailwind');
});

Route::get('tailwind/login', function () {
    return view('test_layout.login');
});

// PHPUnit 测试用例
Route::post('test/user', function (\App\Http\Requests\UserRequest $request) {
    // 参数校验
    $request->validated();
    return $request->hasHeader('X-Header') ? response('test phpunit user'): response('has error', 501);
});

Route::get('test/cookie', function (\Illuminate\Http\Request $request) {
//    dd($request->cookies);
    $color = $request->cookie('color');
    $name = $request->cookie('name');

    return response('test phpunit cookie')->withCookie(cookie('color', $color))->withCookie(cookie('name', $name));
});

Route::post('test/json', function (\Illuminate\Http\Request $request) {
    return response([
        'team' => [
            'owner' => [
                'name' => 'Darian'
            ]
        ]
    ], 201);
});

Route::get('test/file', function () {
    return view('test_file', ['name' => 'test']);
});
Route::post('test/file', function (\Illuminate\Http\Request $request) {
    $path = $request->file('test_file')->store('avatars'); // 默认存储在了 public 驱动

    return $path;
});



// 不需要方法的，单个行为控制器，不需要指定控制器方法，全局就一个方法
Route::get('user_profile/{id}', \App\Http\Controllers\ShowProfileController::class);

// 单个资源返回
Route::get('user/profile', function () {
   if (Auth::guest()) {
       dd('当前未登录');
   } else {
       return new \App\Http\Resources\UserResource(Auth::user());
   }
});

