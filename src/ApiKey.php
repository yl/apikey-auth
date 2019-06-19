<?php

/*
 * This file is part of apikey-auth.
 *
 * (c) Leonis <yangliulnn@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Leonis\ApiKeyAuth;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * App\ApiKeyAuth
 *
 * @property int $id
 * @property int $user_id
 * @property string $key
 * @property string $secret
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ApiKey whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApiKey whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApiKey whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApiKey whereSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApiKey whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApiKey whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ApiKey extends Model
{
    /**
     * Generate api key from user instance.
     *
     * @param \Illuminate\Database\Eloquent\Model $user
     * @return $this
     */
    public static function fromUser(Model $user)
    {
        return self::fromId($user->getKey());
    }

    /**
     * Generate api key from user id.
     *
     * @param $id
     * @return $this
     */
    public static function fromId($id)
    {
        $user = new static();
        $user->user_id = $id;
        $user->key = Str::random(config('api_key.key_length'));
        $user->secret = Str::random(config('api_key.secret_length'));
        $user->save();

        return $user;
    }
}
