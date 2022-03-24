<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    public $table = 'roles';

    public $fillable = [
        'name',
    ];

    public function users()
    {
        // $this->belongsToMany('关联表模型', '中间表名', '本表的外键(在中间表存储的外键)', '关联表的外键(在中间表存储的外键)', '本表的主键', '关联表主键');
        return $this->belongsToMany(User::class, 'role_user', 'role_id', 'user_id')->withTimestamps();
    }
}
