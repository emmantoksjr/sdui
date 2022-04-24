<?php

namespace App\Exceptions;

use Throwable;
use App\Traits\HasJsonResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException as LaravelValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    use HasJsonResponse;

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * {@inheritDoc}
     */
    public function render($request, Throwable $exception)
    {
        $exception = $this->prepareException($exception);

        if ($response = $this->responsableException($exception, $request)) {
            return $response;
        }

        if ($exception instanceof HttpException) {
            return $this->convertHttpExceptionToJson($exception);
        }

        return parent::render($request, $exception);
    }

    /**
     * {@inheritDoc}
     */
    protected function prepareException(Throwable $e)
    {
        if ($e instanceof ModelNotFoundException) {
            return new NotFoundHttpException('Resource not found');
        }elseif ($e instanceof AuthenticationException) {
            return new HttpException(HTTP_UNAUTHORIZED, $e->getMessage(), $e);
        } elseif ($e instanceof UnauthorizedException) {
            return new HttpException(HTTP_FORBIDDEN, $e->getMessage(), $e);
        }

        return parent::prepareException($e);
    }

    protected function convertHttpExceptionToJson(HttpException $exception): JsonResponse
    {
        $statusCode = $exception->getStatusCode();
        $message = $exception->getMessage() ?: Response::$statusTexts[$statusCode];
        $headers = $exception->getHeaders();
        $data = null;

        return $this->jsonResponse($statusCode, $message, $data, $headers);
    }

    protected function responsableException(Throwable $exception, $request): ?JsonResponse
    {
        if ($exception instanceof HttpResponseException) {
            return $this->wrapJsonResponse($exception->getResponse(), 'An error occurred.');
        }

        if ($exception instanceof LaravelValidationException) {
            return (new ValidationException($exception->validator))->render($request);
        }

        return null;
    }
}
