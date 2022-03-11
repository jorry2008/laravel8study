<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Events\UserTestEvent;
use App\Events\UserBroadcastTestEvent;
use Illuminate\Support\Facades\Redis;

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


Route::get('/', function () {

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

    $response  = \Illuminate\Support\Facades\Http::withoutVerifying()->get('https://www.baidu.com/');

    dd($response->headers());


//    $response->body() : string;
//    $response->json() : array|mixed;
//    $response->collect() : Illuminate\Support\Collection;
//    $response->status() : int;
//    $response->ok() : bool;
//    $response->successful() : bool;
//    $response->failed() : bool;
//    $response->serverError() : bool;
//    $response->clientError() : bool;
//    $response->header($header) : string;
//    $response->headers() : array;




});
