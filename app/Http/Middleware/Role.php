<?php

namespace Cupa\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Gate;
use Session;

class Role
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param Guard $auth
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        if ($this->auth->guest()) {
            Session::flash('msg-error', 'Please login to access the requested page.');

            return redirect()->route('home');
        } elseif (Gate::denies('is-'.$role)) {
            return abort(403, 'Permission Denied');
        }

        return $next($request);
    }
}
