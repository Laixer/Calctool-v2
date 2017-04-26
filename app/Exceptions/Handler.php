<?php

namespace BynqIO\CalculatieTool\Exceptions;

use Exception;
use Psr\Log\LoggerInterface;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use \Mail;
use \Auth;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Contracts\Filesystem\FileNotFoundException::class,
        \League\OAuth2\Server\Exception\InvalidClientException::class,
        \League\OAuth2\Server\Exception\InvalidRequestException::class,
        \League\OAuth2\Server\Exception\AccessDeniedException::class,
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    /**
     * Inform administrators of bugreports.
     *
     * In development we ignore this option
     *
     * @param  \Exception  $e
     * @return void
     */
    private function notify(Exception $e)
    {
        $content  = "<pre>Environment: " . app()->environment() . "</pre>";
        $content .= "<pre>Application: " . config('app.name') . "</pre>";
        $content .= "<pre>Timestamp: "   . date('c') . "</pre>";
        $content .= "<pre>Server API: "  . php_sapi_name() . "</pre>";
        $content .= "<pre>Workload: "    . sys_getloadavg()[0] . "</pre>";
        $content .= "<pre>Host: "        . gethostname() . "</pre>";
        $content .= "<pre>Script: "      . $_SERVER['SCRIPT_NAME'] . "</pre>";
        $content .= "<pre>Locale: "      . app()->getLocale() . "</pre>";
        $content .= "<pre>Version: "     . config('app.version') . "</pre>";

        if (request()) {
            $content .= "<pre>Request: " . request()->fullUrl() . "</pre>";
            $content .= "<pre>Remote: "  . request()->ip() . "</pre>";
        }

        if (Auth::check()) {
            $content .= "<pre>User: " . Auth::user()->username . "</pre>";
        }

        $content .= "<br /><b>Stacktrace:</b><br />" . nl2br($e);
        $data = array('content' => $content, 'env' => app()->environment());
        Mail::send('mail.raw', $data, function($message) use ($data) {
            $message->to(ADMIN_EMAIL);
            $message->subject(config('app.name') . ' - Exception report [' . strtoupper($data['env']) . ']');
            $message->from(APP_EMAIL);
        });
    }

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $e)
    {
        if ($this->shouldntReport($e)) {
            return;
        }

        if (!config('app.debug')) {
            $this->notify($e);
        }

        try {
            $logger = $this->container->make(LoggerInterface::class);
        } catch (Exception $ex) {
            throw $e; // throw the original exception
        }

        $logger->error($e);
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
            dd($e);
        }

        return response()->view("errors.common");
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof TokenMismatchException) {
            return back()->withErrors(['csrf' => ['Beveiligingstokens komen niet overeen, probeer opnieuw']]);
        }

        if ($exception instanceof MethodNotAllowedHttpException) {
            $exception = new NotFoundHttpException($exception->getMessage(), $exception);
        }

        return parent::render($request, $exception);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest(route('login'));
    }
}
