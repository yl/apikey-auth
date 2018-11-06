<?php

/*
 * This file is part of apikey-auth.
 *
 * (c) Leonis <yangliulnn@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Leonis\ApiKeyAuth\Middleware;

use Closure;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class TimeDeviationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $timestamp = $request->input('timestamp');
        $deviation = $request->input('deviation', 5000);

        if (abs(millisecond() - $timestamp) > $deviation) {
            throw new BadRequestHttpException();
        }

        return $next($request);
    }
}