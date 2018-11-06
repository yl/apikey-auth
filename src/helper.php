<?php

/*
 * This file is part of apikey-auth.
 *
 * (c) Leonis <yangliulnn@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

if (!function_exists('millisecond')) {
    /**
     * Current time in milliseconds.
     *
     * @return int
     */
    function millisecond()
    {
        return (int)round(microtime(true) * 1000);
    }
}