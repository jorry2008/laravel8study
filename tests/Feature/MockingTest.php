<?php

namespace Tests\Feature;

use App\Jobs\TestJob;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

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
        Queue::fake();

        Queue::assertNotPushed(TestJob::class, 2);

    }
}
