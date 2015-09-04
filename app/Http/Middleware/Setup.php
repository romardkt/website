<?php

namespace Cupa\Http\Middleware;

use Cupa\Page;
use Closure;
use View;
use Request;
use Session;

class Setup
{
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

        $url = Request::url();
        Session::put('previous', $url);

        return $next($request);
    }
}
