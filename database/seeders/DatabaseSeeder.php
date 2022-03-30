<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * 这是一个默认执行文件
     * php artisan db:seed 默认情况下，执行的就是 Database\Seeders\DatabaseSeeder::run();
     *
     * 另外：
     * php artisan migrate --seed 命令，强制执行的就是 DatabaseSeeder::run()
     *
     * @return void
     */
    public function run()
    {
        // 这里应该是 seeder 的总入口，所有需要在迁移就要填充数据的 Seeder 类，统一写到这里进行调用执行。

        (new UserSeeder)->run();
//        (new OrdersSeeder)->run();
//        (new TestSeeder)->run();
    }
}
