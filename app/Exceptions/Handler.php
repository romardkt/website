<?php

namespace Cupa\Exceptions;

use Exception;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Exception\HttpResponseException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Bugsnag\BugsnagLaravel\BugsnagExceptionHandler as ExceptionHandler;

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
        HttpResponseException::class,
        AuthorizationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param \Exception $e
     */
    public function report(Exception $e)
    {
        // set the app version
        app('bugsnag')->setAppVersion(Config::get('app.version'));

        // set the user if authenticated
        if (Auth::check()) {
            app('bugsnag')->setUser(Auth::user()->toArray());
        }

        // send a notification if it is part of the ignored exceptions
        foreach ($this->dontReport as $type) {
            if ($e instanceof HttpResponseException) {
                app('bugsnag')->notifyException($e, ['errors' => Session::get('errors')->toArray()], 'info');
            } elseif ($e instanceof $type) {
                app('bugsnag')->notifyException($e, null, 'info');
            }
        }

        return parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Exception               $e
     *
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if ($e instanceof ModelNotFoundException) {
            $e = new NotFoundHttpException($e->getMessage(), $e);
        }

        // catch the Token Missmatch
        if ($e instanceof TokenMismatchException) {
            return response(view('errors.token'), 401);
        }

        return parent::render($request, $e);
    }
}
