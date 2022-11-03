<?php

namespace Infrastructure\Exceptions;

use Throwable;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Request;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

final class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var string[]
     */
    protected $dontReport = [
        \League\OAuth2\Server\Exception\OAuthServerException::class,
        \Laravel\Passport\Exceptions\OAuthServerException::class,
        \Illuminate\Validation\UnauthorizedException::class,
        \Symfony\Component\HttpKernel\Exception\NotFoundHttpException::class,
        \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Http\Exceptions\ThrottleRequestsException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    /**
     * A list of the forbidden exception types.
     *
     * @var string[]
     */
    protected $forbiddenExceptions = [
        \Illuminate\Auth\Access\AuthorizationException::class,
    ];

    /**
     * A list of the not found exception types.
     *
     * @var string[]
     */
    protected $notFoundExceptions = [
        \Symfony\Component\HttpKernel\Exception\NotFoundHttpException::class,
        \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException::class,
    ];

    /**
     * A list of the throttle exception types.
     *
     * @var string[]
     */
    protected $throttleExceptions = [
        \Illuminate\Http\Exceptions\ThrottleRequestsException::class,
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var string[]
     */
    protected $dontFlash = [
        'password',
    ];

    /**
     * Get the context with request and url variables for logging.
     *
     * @codeCoverageIgnore
     * @return array
     */
    protected function context()
    {
        return array_merge(parent::context(), [
            'url' => Request::fullUrl(),
            'request' => Request::except($this->dontFlash),
        ]);
    }

    private function isInstanceOf(Throwable $e, array $classnames): bool
    {
        foreach ($classnames as $className) {
            if ($e instanceof $className) {
                return true;
            }
        }

        return false;
    }

    protected function isForbiddenException(Throwable $e)
    {
        return $this->isInstanceOf($e, $this->forbiddenExceptions);
    }

    protected function isHttpNotFoundException(Throwable $e)
    {
        return $this->isInstanceOf($e, $this->notFoundExceptions);
    }

    protected function isThrottleException(Throwable $e)
    {
        return $this->isInstanceOf($e, $this->throttleExceptions);
    }

    protected function isModelNotFoundException(Throwable $e)
    {
        return $this->isInstanceOf($e, [ModelNotFoundException::class]);
    }

    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        return response()->errors($e->errors(), $e->status);
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return response()->unauthenticated($exception->getMessage());
    }

    public function render($request, Throwable $e)
    {
        if ($this->isThrottleException($e)) {
            return response()->errorMessage($e->getMessage(), JsonResponse::HTTP_TOO_MANY_REQUESTS);
        }

        if ($this->isModelNotFoundException($e)) {
            return response()->notFound(trans('messages.http.model_not_found', [
                'model' => class_basename($e->getModel()),
            ]));
        }

        if ($this->isHttpNotFoundException($e)) {
            return response()->notFound($e->getMessage() ?: trans('messages.http.404'));
        }

        if ($this->isForbiddenException($e)) {
            return response()->forbidden($e->getMessage());
        }

        return parent::render($request, $e);
    }
}
