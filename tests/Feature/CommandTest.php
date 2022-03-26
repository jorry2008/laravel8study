<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommandTest extends TestCase
{
    use RefreshDatabase;

    /**
     *
     * expectsQuestion() 期望的问题及答案
     * expectsOutput() 期望客户端输出的信息
     * doesntExpectOutput() 不期望...
     * assertFailed() 断言客户端运行失败
     * assertExitCode(0) 期望客户端结束
     *
     * @group abc
     *
     * @return void
     */
    public function test_断言一个命令的整个流程走通()
    {
        $this->artisan('command:test')
            ->expectsQuestion('What is your name?', 'Taylor Otwell') // 问答的所有环节都是匹配的，否则失败
            ->expectsQuestion('Which language do you prefer?', 'PHP') // 回答 PHP 正确
            ->expectsOutput('Your name is Taylor Otwell and you prefer PHP.')
            ->doesntExpectOutput('Your name is Taylor Otwell and you prefer Ruby.')
            ->assertExitCode(0);
    }

    public function test_断言错误回答流程中断()
    {
        $this->artisan('command:test')
            ->expectsQuestion('What is your name?', 'Taylor Otwell') // 问答的所有环节都是匹配的，否则失败
            ->expectsQuestion('Which language do you prefer?', 'PHP5') // 回答 PHP 错误
            ->assertFailed()
            ->assertExitCode(0);
    }

    public function test_断言确认结果()
    {
        $this->artisan('command:test2')
            ->expectsConfirmation('Do you really wish to run this command?', 'yes')
            ->expectsOutput('Your input is yes.')
            ->assertExitCode(0);

        // 期望的表格输出
//        $this->artisan('test2:test2')
//            ->expectsTable([
//                'ID',
//                'Email',
//            ], [
//                [1, 'taylor@example.com'],
//                [2, 'abigail@example.com'],
//            ]);
    }
}
