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

use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class ApiKeyGuard implements Guard
{
    use GuardHelpers;

    /**
     * The request instance.
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    public function __construct(UserProvider $provider, Request $request)
    {
        $this->provider = $provider;
        $this->request = $request;
    }

    /**
     * Get the currently authenticated user.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function user()
    {
        if ($this->user !== null) {
            return $this->user;
        }

        $apiKey = $this->getApiKeyInstance();
        if (!$apiKey) {
            return response()->json([
                'message' => 'The api key is not exist.',
            ], 400);
        }

        $payloads = $this->getPayloads();

        $signature = $this->getSignature();

        if (!$this->checkSignature($payloads, $apiKey->secret, $signature)) {
            return response()->json([
                'message' => 'The signature is invalid.',
            ], 401);
        }

        $this->user = $this->provider->retrieveById($apiKey->user_id);

        return $this->user;
    }

    /**
     * Validate a user's credentials.
     *
     * @param array $credentials
     * @return bool
     */
    public function validate(array $credentials = [])
    {
        return true;
    }

    /**
     * Get a ApiKey instance.
     *
     * @return \App\ApiKey|\Illuminate\Database\Eloquent\Model|null|object
     */
    public function getApiKeyInstance()
    {
        $key = $this->request->header(config('api_key.header'));

        return ApiKey::where('key', $key)->first();
    }

    /**
     * Get payloads from request.
     *
     * @return string
     */
    public function getPayloads()
    {
        $request = $this->request->except('signature');

        ksort($request, SORT_STRING);

        $payloads = '';
        foreach ($request as $key => $value) {
            $payloads .= "$key=$value";
            if (array_key_last($request) !== $key) {
                $payloads .= '&';
            }
        }

        return $payloads;
    }

    /**
     * Get signature from request.
     *
     * @return array|null|string
     */
    public function getSignature()
    {
        return $this->request->input('signature');
    }

    /**
     * Check signature.
     *
     * @param string $payloads
     * @param string $secret
     * @param string $signature
     * @return bool
     */
    public function checkSignature($payloads, $secret, $signature)
    {
        $algo = config('api_key.algo');

        return hash_hmac($algo, $payloads, $secret) === $signature;
    }
}