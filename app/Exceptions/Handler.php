<?php

namespace Cupa\Exceptions;

use Exception;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Bugsnag\BugsnagLaravel\BugsnagExceptionHandler as ExceptionHandler;
// use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        // HttpException::class,
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
        app('bugsnag')->setAppVersion(env('BUGSNAG_APP_VERSION', 'Unknown'));
        // if (get_class($e) != 'Illuminate\Http\Exception\HttpResponseException') {
        //     $this->sendToBugger($e);
        // }

        return parent::report($e);
    }

    // private function sendToBugger($exception)
    // {
    //     $url = 'http://localhost:8000/api/v1/report';
    //     $postData = $_POST;
    //     $accessToken = md5('TestProject');

    //     $frames = [];
    //     foreach ($exception->getTrace() as $frame) {
    //         $frames[] = [
    //             'filename' => isset($frame['file']) ? $frame['file'] : '<internal>',
    //             'lineno' => isset($frame['line']) ? $frame['line'] : 0,
    //             'method' => $frame['function'],
    //             // TODO include args? need to sanitize first.
    //         ];
    //     }

    //     $frames[] = [
    //         'filename' => $exception->getFile(),
    //         'lineno' => $exception->getLine(),
    //     ];

    //     $trace = [
    //         'frames' => $frames,
    //         'message' => get_class($exception).': '.$exception->getMessage(),
    //     ];

    //     $data = [
    //         'url' => $this->getUrl(),
    //         'status_code' => ($exception instanceof NotFoundHttpException) ? 404 : 500,
    //         'method' => Request::method(),
    //         'access_token' => $accessToken,
    //         'timestamp' => time(),
    //         'environment' => App::environment(),
    //         'level' => 'error',
    //         //'language' => 'php',
    //         'person' => (Auth::check()) ? Auth::user()->toArray() : null,
    //         'server' => [
    //             'os' => $this->getOS(),
    //             'broswer' => $this->getBrowser(),
    //             //'data' => $_SERVER,
    //         ],
    //         'ip' => $this->getIp(),
    //         'host' => gethostname(),
    //         'trace' => $trace,
    //     ];

    //     $data['person']['name'] = $data['person']['first_name'].' '.$data['person']['last_name'];

    //     $jsonData = json_encode($data);

    //     $ch = curl_init();
    //     curl_setopt($ch, CURLOPT_URL, $url);
    //     curl_setopt($ch, CURLOPT_POST, true);
    //     curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
    //     curl_setopt($ch, CURLOPT_HTTPHEADER, [
    //         // 'X-Bugger-Access-Token: '.$accessToken,
    //         'Content-Type: application/json',
    //         'Content-Length: '.strlen($jsonData),
    //     ]);
    //     curl_setopt($ch, CURLOPT_VERBOSE, false);
    //     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //     curl_setopt($ch, CURLOPT_TIMEOUT, 3);
    //     curl_setopt($ch, CURLOPT_HTTPHEADER, array());

    //     $result = curl_exec($ch);
    //     $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    //     curl_close($ch);

    //     if ($statusCode != 200) {
    //         Log::warning('Got unexpected status code from Bugger API report: '.$statusCode);
    //         Log::warning('Output: '.$result);
    //     } else {
    //         Log::info('Bugger Success');
    //     }
    // }

    // protected function getIp()
    // {
    //     $forwardFor = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : null;
    //     if ($forwardFor) {
    //         // return everything until the first comma
    //         $parts = explode(',', $forwardFor);

    //         return $parts[0];
    //     }
    //     $realIp = isset($_SERVER['HTTP_X_REAL_IP']) ? $_SERVER['HTTP_X_REAL_IP'] : null;
    //     if ($realIp) {
    //         return $realIp;
    //     }

    //     return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : null;
    // }

    // protected function getUrl()
    // {
    //     if (!empty($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
    //         $proto = strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']);
    //     } elseif (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
    //         $proto = 'https';
    //     } else {
    //         $proto = 'http';
    //     }

    //     if (!empty($_SERVER['HTTP_X_FORWARDED_HOST'])) {
    //         $host = $_SERVER['HTTP_X_FORWARDED_HOST'];
    //     } elseif (!empty($_SERVER['HTTP_HOST'])) {
    //         $parts = explode(':', $_SERVER['HTTP_HOST']);
    //         $host = $parts[0];
    //     } elseif (!empty($_SERVER['SERVER_NAME'])) {
    //         $host = $_SERVER['SERVER_NAME'];
    //     } else {
    //         $host = 'unknown';
    //     }

    //     if (!empty($_SERVER['HTTP_X_FORWARDED_PORT'])) {
    //         $port = $_SERVER['HTTP_X_FORWARDED_PORT'];
    //     } elseif (!empty($_SERVER['SERVER_PORT'])) {
    //         $port = $_SERVER['SERVER_PORT'];
    //     } elseif ($proto === 'https') {
    //         $port = 443;
    //     } else {
    //         $port = 80;
    //     }

    //     $path = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';

    //     $url = $proto.'://'.$host;

    //     if (($proto == 'https' && $port != 443) || ($proto == 'http' && $port != 80)) {
    //         $url .= ':'.$port;
    //     }

    //     $url .= $path;

    //     return $url;
    // }

    // protected function getOS()
    // {
    //     $osPlatform = 'Unknown OS Platform';
    //     $osArray = [
    //         '/windows nt 10/i' => 'Windows 10',
    //         '/windows nt 6.3/i' => 'Windows 8.1',
    //         '/windows nt 6.2/i' => 'Windows 8',
    //         '/windows nt 6.1/i' => 'Windows 7',
    //         '/windows nt 6.0/i' => 'Windows Vista',
    //         '/windows nt 5.2/i' => 'Windows Server 2003/XP x64',
    //         '/windows nt 5.1/i' => 'Windows XP',
    //         '/windows xp/i' => 'Windows XP',
    //         '/windows nt 5.0/i' => 'Windows 2000',
    //         '/windows me/i' => 'Windows ME',
    //         '/win98/i' => 'Windows 98',
    //         '/win95/i' => 'Windows 95',
    //         '/win16/i' => 'Windows 3.11',
    //         '/macintosh|mac os x/i' => 'Mac OS X',
    //         '/mac_powerpc/i' => 'Mac OS 9',
    //         '/linux/i' => 'Linux',
    //         '/ubuntu/i' => 'Ubuntu',
    //         '/iphone/i' => 'iPhone',
    //         '/ipod/i' => 'iPod',
    //         '/ipad/i' => 'iPad',
    //         '/android/i' => 'Android',
    //         '/blackberry/i' => 'BlackBerry',
    //         '/webos/i' => 'Mobile',
    //     ];

    //     foreach ($osArray as $regex => $value) {
    //         if (preg_match($regex, $_SERVER['HTTP_USER_AGENT'])) {
    //             $osPlatform = $value;
    //         }
    //     }

    //     return $osPlatform;
    // }

    // public function getBrowser()
    // {
    //     $browser = 'Unknown Browser';
    //     $browserArray = [
    //         '/msie/i' => 'Internet Explorer',
    //         '/firefox/i' => 'Firefox',
    //         '/safari/i' => 'Safari',
    //         '/chrome/i' => 'Chrome',
    //         '/edge/i' => 'Edge',
    //         '/opera/i' => 'Opera',
    //         '/netscape/i' => 'Netscape',
    //         '/maxthon/i' => 'Maxthon',
    //         '/konqueror/i' => 'Konqueror',
    //         '/mobile/i' => 'Handheld Browser',
    //     ];

    //     foreach ($browserArray as $regex => $value) {
    //         if (preg_match($regex, $_SERVER['HTTP_USER_AGENT'])) {
    //             $browser = $value;
    //         }
    //     }

    //     return $browser;
    // }

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
