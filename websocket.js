var server = require('http').Server();
var io = require('socket.io')(server, {
    cors: {
        origin: "http://laravel8.cc", // 跨域
        methods: ["GET", "POST"]
    }
});

var Redis = require('ioredis');
var redis = new Redis({
    host: 'localhost',
    port: 6379
});

redis.subscribe('test-channel');
redis.on('message', function (channel, message) {
    console.log(channel, message);
    message = JSON.parse(message);
    io.emit(channel + ":" + message.event, message.data);
});

server.listen(3000, () => {
    console.log('http server started and listen on 3000. 监听 3000 端口对外提供 socket 服务');
});
