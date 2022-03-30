<?php

namespace Tests\Feature;

use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DbTest extends TestCase
{
    use RefreshDatabase; // 每个用户测试完成，都会 migrate:fresh

    public function test_数据库()
    {
        $order = Order::factory()->make(); // 模拟数据
        $orders = Order::factory()->count(3)->make();
        $orders = Order::factory()->count(5)->suspended()->make(); // 调用 builder 方法间接调用 state() 方法临时修改属性
        $order = Order::factory()->make([
            'name' => 'Abigail Otwell',
        ]); // 传入参数，临时修改属性
        $order = Order::factory()->state([
            'name' => 'Abigail Otwell',
        ])->make(); // 直接使用 state() 方法临时修改属性

        // 以上 make() 方法，只是用来生成测试数据，并返回对应的模型对象，不会入库，换成 create() 方法可实现持久化入库

        $this->assertTrue(true);
    }
}
