<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Jobs\TestJob;
use App\Mail\TestMail;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Mail\PendingMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;

class MockingTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_mocking_example()
    {
//        Storage::fake();
        Queue::fake();
//        Event::fake();
//        Mail::fake();
//        Notification::fake();
//        Bus::fake();

//        Queue::assertNotPushed();

//        Bus::dispatch();

//        Queue::assertPushedOn('queue-name', TestJob::class);

//        Queue::assertPushed(TestJob::class, 2);

//        Queue::assertPushed(function () {
//            return true;
//        });

//        \Mockery::mock('');

        // 断言发送了邮件……
//        Mail::assertSent(TestMail::class, function ($mail) {
//            return true;
//        });

        // 断言邮件发送了两遍
//        Mail::assertSent(OrderShipped::class, 2);
        // 断言邮件未发送
//        Mail::assertNotSent(AnotherMailable::class);

    }
}
