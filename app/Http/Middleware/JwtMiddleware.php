<?php

namespace App\Http\Middleware;

use App\Models\SocialUser;
use Closure;
use Exception;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return $this->responseWarning('Token is Invalid', 401);
            } else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return $this->responseWarning('Token is Expired', 401);
            } else {
                return $this->responseWarning('Authorization Token not found', 401);
            }
        }
        //to ensure that is correct
        if(!$user instanceof SocialUser){
            return $this->responseWarning('Authorization Token not found', 401);
        }
        return $next($request);
    }

    public function responseWarning($message, $code)
    {
        return response([
            "status" => $code,
            "success" => false,
            "message" => $message,
        ], $code);
    }
}
