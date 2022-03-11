<?php

namespace App\Models;

use Illuminate\Database\Eloquent\BroadcastsEvents;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public $table = 'order';

    use HasFactory, BroadcastsEvents;

    public $fillable = [
        'name',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
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
}
