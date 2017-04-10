<?php

namespace CalculatieTool\Exceptions;

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
     * Report or log an exception.
     *
     * @param  \Exception  $e
     * @return void
     *
     * @throws \Exception
     */
    public function report(Exception $e)
    {
        if ($this->shouldntReport($e)) {
            return;
        }

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
            Mail::send('mail.raw', $data, function($message) use ($data) {
                $message->to('y.dewid@calculatietool.com', 'Yorick de Wid');
                $message->to('d.zandbergen@calculatietool.com', 'Don Zandbergen');
                $message->subject('CalculatieTool.com - Exception report [' . $data['env'] . ']');
                $message->from('info@calculatietool.com', 'CalculatieTool.com');
                $message->replyTo('info@calculatietool.com', 'CalculatieTool.com');
            });
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
            return parent::convertExceptionToResponse($e);
        }

        return response()->view("errors.common", ['exception' => $e]);
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
        if ($e instanceof ModelNotFoundException) {
            $e = new NotFoundHttpException($exception->getMessage(), $exception);
        }

        if ($e instanceof TokenMismatchException) {
            return back()->withErrors(['csrf' => ['Beveiligingstokens komen niet overeen, probeer opnieuw']]);
        }

        if ($e instanceof MethodNotAllowedHttpException) {
            abort(404);
        }

        return parent::render($request, $e);
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
