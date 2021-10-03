<?php

namespace App\Exceptions;



use App\Constants\Status;
use App\Helpers\Helper;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

   
    public function render($request, Throwable $exception)
    {
        if ($request->wantsJson()) {
            if ($exception instanceof AuthenticationException) {
                return response()->json(['message' => $exception->getMessage()]);
            } elseif ($exception instanceof AuthorizationException || $exception instanceof UnauthorizedException) {
                return response()->json(['message' => "Unauthorized"]);
            } elseif ($exception instanceof ModelNotFoundException) {
                return response()->json(['message' => "Model not found"]);
            } elseif ($exception instanceof NotFoundHttpException) {
                return response()->json(['message' => "Route not found"]);
            } elseif ($exception instanceof ValidationException) {
                return response()->json(['message' => "Validation error", 'data' => $exception->errors()]);
            } else {
                return response()->json([
                    'message' => $exception->getMessage(),
                ], 500);
            }
        }

        return parent::render($request, $exception);
    }
}
