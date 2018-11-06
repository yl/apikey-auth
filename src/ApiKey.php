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
    public function fromUser(Model $user)
    {
        return $this->fromId($user->getKey());
    }

    /**
     * Generate api key from user id.
     *
     * @param $id
     * @return $this
     */
    public function fromId($id)
    {
        $this->user_id = $id;
        $this->key = Str::random(config('key.key_length'));
        $this->secret = Str::random(config('key.secret_length'));
        $this->save();

        return $this;
    }
}
