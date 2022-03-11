<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/dashboard';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    // protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::prefix('api')
                ->middleware('api') // 这里引入了中间件组，名称为 api，而api中间件组包括多个中间件，其中一个叫 'throttle:api', 为系统指定了限流器
                ->namespace($this->namespace)
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));

        });
    }

    /**
     * Configure the rate limiters for the application.
     * 为当前应用配置自定义限流器
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        // 默认的限流器
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip()); // 登录状态使用 user_id 作为 key，非登录状态使用 ip 作为 key。
        });

        // 限流器2
        RateLimiter::for('api2', function (Request $request) {
            return Limit::perMinute(60)
                ->by(optional($request->user())->id ?: $request->ip())
                ->response(function () {
                    return response('超出限制访问次数...', 429); // 在HTTP协议中，响应状态码 429 Too Many Requests 表示在一定的时间内用户发送了太多的请求，即超出了“频次限制”。
                });
        });

        // 限流器3（可变限流器）
        RateLimiter::for('api3', function (Request $request) {
            return $request->user()->vipCustomer() // 高级用户，提高限流次数 ^_^
                ? Limit::none() // 高级用户不限制访问
                : Limit::perMinute(100)->by(optional($request->user())->id ?: $request->ip());
        });

        // 限流器4
        RateLimiter::for('login', function (Request $request) {
            // 两次评估，属于 and 关系，任何一个限流器不通过，都将禁止访问
            return [
                Limit::perMinute(500),
                Limit::perMinute(3)->by($request->input('email')), // 当用户提交邮件时，只限流 3 次，这里可以是任何条件！！！
            ];
        });

        // Limit 还有其它用法
        Limit::perHour(600); // 每小时 600次
        Limit::perHour(600, 2); // 每2小时 600次
        Limit::perDay(1000, 3); // 每3天 1000次

        // 限流的方式多种多样，可以自定义限流条件，也可以将业务逻辑融合进来，还能多限流器组合！
        // 比如：通过 Limit::perMinute(100)->by() 方法，根据IP实现全球不同区域的限流设置
    }
}
