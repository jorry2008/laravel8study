<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ShowProfileController extends Controller
{
    /**
     * Handle the incoming request.
     * 这是一个单个行为控制器，全局就一个方法，路由绑定时不需要指定具体的方法
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke($id, Request $request)
    {
        dd('这是单个行为控制器...'.'传入的内容为：'.$id);
    }
}
