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
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use League\OAuth2\Server\Exception\InvalidClientException;
use League\OAuth2\Server\Exception\InvalidRequestException;
use League\OAuth2\Server\Exception\AccessDeniedException;

use \Mailgun;
use \Auth;

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
        FileNotFoundException::class,
        InvalidClientException::class,
        InvalidRequestException::class,
        AccessDeniedException::class,
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
        if ($this->shouldReport($e)) {
            $this->log->error($e);

            if (!config('app.debug')) {
                $request = request();
                $content = "<pre>Environment: " . app()->environment() . "</pre>";
                $content .= "<pre>Timestamp: " . date('c') . "</pre>";
                $content .= "<pre>Server API: " . php_sapi_name() . "</pre>";
                $content .= "<pre>Workload: " . sys_getloadavg()[0] . "</pre>";
                $content .= "<pre>Host: " . gethostname() . "</pre>";
                $content .= "<pre>Script: " . $_SERVER['SCRIPT_NAME'] . "</pre>";

                $rev = '-';
                if (\File::exists('../.revision')) {
                    $rev = \File::get('../.revision');
                }

                $content .= "<pre>Revision: " . $rev . "</pre>";

                if ($request) {
                    $content .= "<pre>Request: " . $request->fullUrl() . "</pre>";
                }

                if (Auth::check())
                    $content .= "<pre>User: " . Auth::user()->username . "</pre>";

                $content .= "<br /><pre>Stacktrace:</pre><br />" . nl2br($e);
                $data = array('content' => $content, 'env' => app()->environment());
                Mailgun::send('mail.raw', $data, function($message) use ($data) {
                    $message->to('y.dewid@calculatietool.com', 'Yorick de Wid');
                    $message->to('d.zandbergen@calculatietool.com', 'Don Zandbergen');
                    $message->subject('CalculatieTool.com - Exception report [' . $data['env'] . ']');
                    $message->from('info@calculatietool.com', 'CalculatieTool.com');
                    $message->replyTo('info@calculatietool.com', 'CalculatieTool.com');
                });
            }
        }
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
