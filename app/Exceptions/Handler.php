<?php

namespace App\Exceptions;

use Common\Utils\ApiJsonResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (AuthenticationException $e) {
            $str = $e->getMessage();
            if ($str == "Unauthenticated.") {
                $str = null;
            }
            return ApiJsonResponse::unauthenticated($str ?? "handle exception: Auth middleware.");
        });

        // $this->renderable(function (Throwable $e) {
        //     return ApiJsonResponse::error($e->getMessage(), ApiJsonResponse::ErrCode['InternalServerError']);
        // });
    }
}
