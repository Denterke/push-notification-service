<?php

namespace App\Http\Middleware;

use App\Signature\Signer;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class VerifyRequest
{
    /**
     * @var Signer
     */
    protected $signer;

    public function __construct(Signer $signer)
    {
        $this->signer = $signer;
    }

    public function handle(Request $request, Closure $next)
    {
        Log::debug('Service request');
        Log::debug(PHP_EOL);
        Log::debug($request->getContent());
        Log::debug($request->header('X-Hash', ''));
        Log::debug((int)$request->header('X-Time', 0));
        if (!$this->signer->verify(
            $request->getContent(),
            $request->header('X-Hash', ''),
            (int)$request->header('X-Time', 0))
        ) {
            abort(401, 'Sign error');
        }
        return $next($request);
    }

}
