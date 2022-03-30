<?php

namespace Tests\Feature\Ui;

use Database\Factories\UserFactory;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Routing\Pipeline;
use Illuminate\Support\Facades\Auth;
use Illuminate\Testing\TestResponse;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class AuthenticatesUsersTest extends TestCase
{
    use AuthenticatesUsers;

    protected function tearDown(): void
    {
        Auth::logout();

        parent::tearDown();
    }

    /** @test */
    public function test_it_can_authenticate_a_user()
    {
        $user = UserFactory::new()->create();

        $request = Request::create('/login', 'POST', [
            'email' => $user->email,
            'password' => 'password',
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json',
        ]);

        $response = $this->handleRequestUsing($request, function ($request) {
            return $this->login($request);
        })->assertStatus(204);
    }

    /** @test */
    public function test_it_cant_authenticate_a_user_with_invalid_password()
    {
        $user = UserFactory::new()->create();

        $request = Request::create('/login', 'POST', [
            'email' => $user->email,
            'password' => 'invalid-password',
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json',
        ]);

        $response = $this->handleRequestUsing($request, function ($request) {
            return $this->login($request);
        })->assertUnprocessable();

        $this->assertInstanceOf(ValidationException::class, $response->exception);
        $this->assertSame([
            'email' => [
                'These credentials do not match our records.',
            ],
        ], $response->exception->errors());
    }

     /** @test */
    public function test_it_cant_authenticate_unknown_credential()
    {
        $request = Request::create('/login', 'POST', [
            'email' => 'taylor@laravel.com',
            'password' => 'password',
        ], [], [], [
            'HTTP_ACCEPT' => 'application/json',
        ]);

        $response = $this->handleRequestUsing($request, function ($request) {
            return $this->login($request);
        })->assertUnprocessable();

        $this->assertInstanceOf(ValidationException::class, $response->exception);
        $this->assertSame([
            'email' => [
                'These credentials do not match our records.',
            ],
        ], $response->exception->errors());
    }

    /**
     * Handle Request using the following pipeline.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  callable  $callback
     * @return \Illuminate\Testing\TestResponse
     */
    protected function handleRequestUsing(Request $request, callable $callback)
    {
        return new TestResponse(
            (new Pipeline($this->app))
                ->send($request)
                ->through([
                    \Illuminate\Session\Middleware\StartSession::class,
                ])
                ->then($callback)
        );
    }
}
