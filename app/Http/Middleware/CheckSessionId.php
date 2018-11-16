<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;

class CheckSessionId
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $sessionId = $request->input('session_id');
        $miniUser = Cache::get($sessionId);
        if (empty($miniUser['session_key'])) {
            return response()->json([
                'code'   => 201,
                'data'   => '用户未登录',
            ]);
        }
        $request->offsetSet('miniUser', $miniUser);

        return $next($request);
    }
}
