<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Support\Facades\Log;

/**
 * 注意使用技巧：
 * 1.使用工厂时，批量分配保护（$fillable字段将无效）
 * 2.默认情况下，通过命令创建的类，每个模型和模型工厂是对应的
 * 3.如果工厂批不到模型，请定义 $model 属性
 * 4.如果模型找不到工厂，请重写 newFactory() 方法，并返回正确的模型工厂对象
 * 5.make()方法用来返回模型对象但不入库（测试用），create()方法用来新建并入库（模拟数据用）
 *
 */
class OrderFactory extends Factory
{
    // 这个属性不需要绑定，默认是 Database\Factories\OrderFactory 前面部分与模型名称直接匹配自动查找，会自动查找 生成 App\Models\Order
    // Database\Factories\Cate\TestFactory => App\Models\Cate\TestModel，如果不符合这个规则，则需要手动填充以下 $model 属性
//    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'test' => $this->faker->text(20),
            'user_id' => 1,
        ];
    }

    /**
     * 状态操作方法允许你定义离散修改，可以以任意组合应用于模型工厂。
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function suspended()
    {
        // 状态转换方法通常调用 Laravel 的基础工厂类提供的 state 方法 。
        // state 方法接收一个回调将收到为工厂定义的原始属性数组，并且会返回一个要修改的属性数组
        return $this->state(function (array $attributes) { // 局部修改的数组
            return [
                'test' => 'abc',
//                new Sequence(
//                    ['user_id' => User::all()->random()]
//                )
            ];
        });
    }

    /**
     * 配置模型工厂
     * 你应该通过在工厂类上定义 configure 方法来注册这些回调。
     * @return $this
     */
    public function configure()
    {
        // 工厂回调是使用 afterMaking 和 afterCreating 方法注册的，并且允许你在创建模型之后执行其他任务。
        return $this->afterMaking(function (Order $order) {
            Log::info('生成数据之前');
        })->afterCreating(function (Order $order) {
            Log::info('生成数据之后');
        });
    }
}
