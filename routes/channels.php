<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| 私有频道授权
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

//Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
//    return (int) $user->id === (int) $id;
//});

// 私有频道，需要频道授权
// 所有授权回调都接收至少一个参数：当前认证用户作为第一个参数、以及任意额外通配符参数作为随后参数，在本例中，我们使用 {id} 占位符标识频道名称的ID部分是一个通配符。
// laravel-echo-server 在后台运行，触发
Broadcast::channel('wechat.{id}', function (\App\Models\User $user, $id) {
    // 授权逻辑，通过则向指定频道分发消息，不通过就不发（每次 laravel-echo-server 启动连接私有频道时，都会执行一次认证）
//    return $user->id == $id;
    return true;
});

// 也可以将此回调换成自定义频道类的 join
//Broadcast::channel('order_channel.{order}', \App\Broadcasting\WechatChannel::class);

// 存在频道
// 假设，id为1是已经认证的当前用户，id为2的是后面加入监听的用户
Broadcast::channel('wechat.group.{id}', function (\App\Models\User $user, $id) {
    // 当你验证一个存在渠道时，如果用户被允许，你必须返回一个关于这个用户的数据数组。
    return ['id' => $user->id, 'name' => $user->name];
//    if ($user->canJoinRoom($roomId)) {
//        return ['id' => $user->id, 'name' => $user->name];
//    }
});

// 模型广播
Broadcast::channel('App.Models.Order.{id}', function (\App\Models\User $user, $id) {
    return true;
});
Broadcast::channel('App.Models.User.{id}', function (\App\Models\User $user, $id) {
    return true;
});
