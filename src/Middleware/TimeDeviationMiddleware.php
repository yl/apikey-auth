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
use Illuminate\Support\Facades\Validator;
use Leonis\ApiKeyAuth\Exceptions\ApiKeyAuthException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class TimeDeviationMiddleware
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
        $timestamp  = $request->input('timestamp');
        $timeoffset = $request->input('timeoffset');

        if ($timestamp === null || $timeoffset === null) {
            throw new ApiKeyAuthException('Timestamp and timeoffset is required.', 400);
        }

        if (abs(time() - $timestamp) > $timeoffset) {
            throw new ApiKeyAuthException('Request has expired.', 400);
        }

        return $next($request);
    }
}