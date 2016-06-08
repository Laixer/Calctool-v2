<?php

namespace Calctool\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        HttpException::class,
        ModelNotFoundException::class,
        AuthorizationException::class,
        ValidationException::class,
        TokenMismatchException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        return parent::report($e);
    }

    /**
     * Create a Symfony response for the given exception.
     *
     * In production we ignore this and return common blade error
     *
     * @param  \Exception  $e
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function convertExceptionToResponse(Exception $e)
    {
        if (config('app.debug')) {
            return parent::convertExceptionToResponse($e);
        }

        return response()->view("errors.common", ['exception' => $e]);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if ($e instanceof ModelNotFoundException) {
            $e = new NotFoundHttpException($e->getMessage(), $e);
        }

        if ($e instanceof TokenMismatchException) {
            return back()->withErrors(['csrf' => ['Beveiligingstokens komen niet overeen, probeer opnieuw']]);
        }

        if ($e instanceof MethodNotAllowedHttpException) {
            abort(404);
        }

        return parent::render($request, $e);
    }
}
