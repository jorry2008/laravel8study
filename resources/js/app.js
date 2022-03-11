require('./bootstrap');

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();


// 公共频道
// Echo.channel('push')
//     .listen('.push.message', (event) => {
//         alert(event.message);
//     });

// 私有频道
// Echo.private('wechat.' + id)
//     .listen('.push.message', (event) => {
//         console.log(event.user.name + ' Says ' + event.message);
//     });

// 存在频道
// let groupId = 100;
// Echo.join('wechat.group.' + groupId)
//     .listen('.push.message', (event) => {
//         // 监听&接收服务端广播的消息
//         console.log(event.user.name + '加入了群聊');
//     }).here((event) => {
//         console.log(event);
//     })
//     .joining((event) => {
//         console.log(event);
//     })
//     .leaving((event) => {
//         console.log(event);
//     });

// console.log('SocketId：' + Echo.socketId());

// 模型广播
// Echo.private('App.Models.Order.19')
//     .listen('.OrderUpdated', (event) => {
//         console.log(event);
//     });
// Echo.private('App.Models.User.1')
//     .listen('.OrderUpdated', (event) => {
//         console.log('测试测试啊');
//     });
