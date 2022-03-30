<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class OrdersSeeder extends Seeder
{
    protected function tttt()
    {
        // 强大的表关联批量创建数据
        // 默认关联
        $user = User::factory()
            ->count(1)
            ->has(Order::factory()->count(2)) // 每1个用户对应创建3个订单，默认约定调用Order的关系方法：orders() 【不写的都是符合约定的】
            ->create();

        // 手动关联
        $user = User::factory()
            ->count(1)
            ->has(Order::factory()->count(2), 'orders') // 明确指定关联方法，不符合约定的，在这里手动指定
            ->create();

        // 综合使用
        $user = User::factory()
            ->count(1)
            ->has(
                // 用法与单独使用完全一样
                Order::factory()
                    ->count(2)
                    ->state(function (array $attributes, User $user) { // 当前是由 User 执行的方法，还属于 User 执行体内，所以这里使用 User 类型提示，可以精准找到当前 User 模型！！！
                        return ['name' => $user->email];
                    })
            )
            ->create();

        // 魔术用法
        $user = User::factory()
                ->count(1)
                ->hasOrders(2) // 自动寻找 User 模型中的 orders() 方法 【强】
                ->create();

        $user = User::factory()
            ->count(1)
            ->hasOrders(2, [
                'name' => 'ddddddddddd'
            ]) // 自动寻找 User 模型中的 orders() 方法，还能自定义 order 中的属性
            ->create();

        $user = User::factory()
            ->count(1)
            ->hasOrders(2, function (array $attributes, User $user) {
                return ['name' => $user->email];
            }) // 自动寻找 User 模型中的 orders() 方法，以回调的方式自定义 order 中的属性
            ->create();

        // 从属关系，用法基本一致，将 has() 方法换成 for() 方法即可，比如：
        $orders = Order::factory()
            ->count(2)
            ->for(User::factory() // 这里只有唯一，所以不能使用 count() 方法【注意】
                ->state([
                    'name' => '从属创建',
                ]), 'user')
            ->create();

        // 有一种特殊的情况，即 User 对象已经存在，直接 for() 这个对象即可
        $user = User::factory()->create();
        $posts = Order::factory()
            ->count(2)
            ->for($user)
            ->create();

        // 同样也可以使用魔术方法
        $orders = Order::factory()
            ->count(2)
            ->forUser([ // User 对应 Order 中的 user() 方法名
                'name' => '魔术for方法',
            ])
            ->create();






    }

    /**
     * Run the database seeds.
     * 这个功能，我给 100 个赞
     * 从原始的手动插入，到工厂自动化，再到多表关联，最后到组合调用...简直就是保姆式服务。
     *
     * @return void
     */
    public function run()
    {
        $this->tttt();
        exit(0);


        // 方案一：批量生成数据，可以直接使用数据库操作方法，推荐使用模式工厂
//        DB::table('users')->insert([
//            'name' => Str::random(10),
//            'email' => Str::random(10).'@gmail.com',
//            'password' => Hash::make('password'),
//        ]);


        // 方案二：使用模型工厂
        // make() 和 create() 方法：
        // 使用 make 方法来创建模型而不持续到数据库，调用 make() 方法后，返回具体的模型，但不会入库，主要用来测试用。
        // 使用 create() 方法，将数据写入到数据库并返回模型对象，用来真实创建数据。
        // 两个方法都可以传入属性数组，用来临时将指定的属性替换，而这些属性的其余部分保持设置为其默认值。


//        Order::factory()->count(10)->make(); // 模拟生成
        $orders = Order::factory()->count(2)->create(); // 批量创建入库

        // 使用 状态操作 功能，临时修改局部字段
        $order = Order::factory()->suspended()->create();

        // 模型工厂，可以临时使用新值覆盖
        $order = Order::factory()->create([ // 为什么这条先执行？？？
            'name' => 'Abigail jorry',
        ]);

        // 随机序列
//        $order = Order::factory()->state(new Sequence(
//            ['user_id' => 8],
//            ['user_id' => 9],
//            ['user_id' => 10],
//        ))->create([
//            'name' => 'Abigail jorry222',
//        ]);

        // 使用 state() 方法，实现多表随机关联
        Order::factory()->count(3)->state(new Sequence(
            fn () => ['user_id' => User::all()->random()] // php 装饰写法，装饰函数返回数组，不影响原来的结果，所有地方其实都可以这样用
        ))->create([
            'name' => '多表测试',
        ]);
        // 通过以上例子，可以看到闭包的作用，就是为了简化代码的，本来两行代码，直接整成一行










        // 方案三：Seeder 组合
        // 在 XxxxxSeeder 类中，您可以使用 call 方法来运行其他的 seed 类。这功能太强了，根据业务有机组合，可以生成比较全面的数据。
        // Seeder 调用其它 Seeder，这个有点脑洞了...
//        $this->call([
//            UserSeeder::class,
//            PostSeeder::class,
//            CommentSeeder::class,
//        ]);

    }
}
