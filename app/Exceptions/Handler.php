<?php

namespace Cupa\Exceptions;

use Exception;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
        if (App::environment() == 'production') {
            $config = Config::get('rollbar');
            $config['person'] = (Auth::check()) ? Auth::user()->toArray() : 'No User';
            \Rollbar::init($config);

            if ($e instanceof NotFoundHttpException) {
                \Rollbar::report_message('Page not found: '.Request::url(), 'info');
            } elseif (get_class($e) != 'Illuminate\Http\Exception\HttpResponseException') {
                \Rollbar::report_exception($e);
            }
        }

        if (get_class($e) != 'Illuminate\Http\Exception\HttpResponseException') {
            $this->sendToBugger($e);
        }

        return parent::report($e);
    }

    private function sendToBugger($exception)
    {
        $url = 'http://localhost:8000/api/v1/report';
        $postData = $_POST;
        $accessToken = md5('TestProject');

        $frames = [];
        foreach ($exception->getTrace() as $frame) {
            $frames[] = [
                'filename' => isset($frame['file']) ? $frame['file'] : '<internal>',
                'lineno' => isset($frame['line']) ? $frame['line'] : 0,
                'method' => $frame['function'],
                // TODO include args? need to sanitize first.
            ];
        }

        $frames[] = [
            'filename' => $exception->getFile(),
            'lineno' => $exception->getLine(),
        ];

        $trace = [
            'frames' => $frames,
            'message' => get_class($exception).': '.$exception->getMessage(),
        ];

        $data = [
            'status_code' => ($exception instanceof NotFoundHttpException) ? 404 : 500,
            'method' => Request::method(),
            'access_token' => $accessToken,
            'timestamp' => time(),
            'environment' => App::environment(),
            'level' => 'error',
            //'language' => 'php',
            'person' => (Auth::check()) ? Auth::user()->toArray() : null,
            // 'server' => $_SERVER,
            'ip' => '111.111.111.111',
            'host' => gethostname(),
            'trace' => $trace,
        ];

        $data['person']['name'] = $data['person']['first_name'].' '.$data['person']['last_name'];

        $jsonData = json_encode($data);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            // 'X-Bugger-Access-Token: '.$accessToken,
            'Content-Type: application/json',
            'Content-Length: '.strlen($jsonData),
        ]);
        curl_setopt($ch, CURLOPT_VERBOSE, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array());

        $result = curl_exec($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($statusCode != 200) {
            Log::warning('Got unexpected status code from Bugger API report: '.$statusCode);
            Log::warning('Output: '.$result);
        } else {
            Log::info('Bugger Success');
        }
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

        return parent::render($request, $e);
    }
}
