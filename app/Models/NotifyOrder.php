<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;

class NotifyOrder extends Model
{
    use HasFactory, Notifiable;

    public $table = 'order';

    public $fillable = [
        'name',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 这种用法非常别扭，但很具有深意！！！
     * 首先，它只支持一个用户，其次，这个方法强制在实现模型上，非常具有实际意义：（不足之外是，开发人员配置了MailChannel频道，邮件接受人参数应该强制提示抛出异常）
     * 强制关联用户，即一个推送模型不管直接或者间接，都会关联到用户，否则消息通知就没有意义，至少要保证接收人的存在！！！
     */
    public function routeNotificationForMail(Notification $notification)
    {
        // 如果当前模型字段支持的话
//        return $this->email_address;
//        return [$this->email_address => $this->name];

        return [$this->user->email => $this->user->name];
    }

    /**
     * 实现些方法，可以自定义数据库消息存储的新结构！！
     */
//    public function routeNotificationForDatabase()
//    {
//        // 业务逻辑
//        return $this->notifications();
//    }
}
