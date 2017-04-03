<?php

namespace Cupa\Http\Middleware;

use View;
use Request;
use Session;
use Closure;
use Route;
use Cupa\Models\Page;

class Setup
{
    protected $ignoredPaths = [
        'logout',
        'login',
        'auth/facebook/callback',
        'auth/facebook/redirect',
    ];

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        View::share('isYouth', 0);
        $menu = Page::fetchMenu();
        View::share('mainMenu', $menu['root']);
        $path = Request::path();
        $parts = explode('/', $path);
        View::share('pageRoot', $parts[0]);
        if (isset($menu[$parts[0]])) {
            View::share('subMenus', $menu[$parts[0]]);
        }

        $url = $request->route()->uri;
        if (!in_array($url, $this->ignoredPaths)) {
            Session::put('previous', $url);
        }

        return $next($request);
    }
}
