<?php

namespace App\Models;

use Database\Factories\Cate\TestModelFactory;
use Illuminate\Database\Eloquent\BroadcastsEvents;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public $table = 'orders';

    // 为什么 HasFactory 中会有 newFactory() 方法？因为模型工厂与模型本身有一个对应关系，所以可以直接调用 factory 便可识别对应的模型工厂类，
    // 但两者之间没有对应关系时怎么办？答：重写 newFactory() 方法，返回新的模型工厂即可。
    use HasFactory, BroadcastsEvents;

    public $fillable = [
        'name',
        'test',
        'user_id',
    ];

    // 表关联
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function broadcastOn($event)
    {
        return [$this, $this->user]; // 两个模型，对应两个通道，App.Models.Order.19 和 App.Models.User.1
    }

//    public function broadcastOn($event)
//    {
//        if ($event == 'created')
//            return [$this];
//    }

//    public function broadcastAs($event)
//    {
//        return 'push.message';
//    }

    public function broadcastWith($event)
    {
        return ['message' => '响应的数据，自定义', 'order' => $this];
    }

    /**
     * 如果模型与模型工厂无直接对应关系，则需要重写以下方法返回正确的模型工厂
     * 定义了 newFactory() 自动覆盖原来的 factory()
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
//    protected static function newFactory()
//    {
//        return TestModelFactory::new();
//    }
}
