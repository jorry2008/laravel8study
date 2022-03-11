<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

### 已经完成的功能列表

- 事件系统【完成】
- 队列【完成】
- 发送邮件【完成】
- 广播系统【完成】
- 消息通知【完成】
- 任务调度【完成】
- 限流【完成】
- http客户端
- 契约和门面
- 编译前端MIX
- 日志
- 拓展包开发
- Redis
- 用户认证
- 用户授权

以上挑选出来的是比较核心的功能特性，其它没写的部分并不复杂就不讨论了。

使用流程：
```shell
npm install
npm run watch
npm install -g laravel-echo-server@1.6.3

composer install

# 先连接数据库和redis
php artisan migrate
php artisan queue:listen

laravel-echo-server start
```
