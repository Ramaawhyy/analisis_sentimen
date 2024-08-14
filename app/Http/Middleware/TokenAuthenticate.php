<?php

namespace App\Http\Middleware;
use App\Models\Token;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Tymon\JWTAuth\Facades\JWTAuth;


class TokenAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Mendapatkan token dari header Authorization
        $token = $request->bearerToken();

        // Cek apakah token disertakan dalam header Authorization
        if (!$token) {
            return response()->json(['error' => 'Unauthorized - Token not provided'], 401);
        }

        try {
            // Decode the token and get the payload
            $tokenData = JWTAuth::decode($token);
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['error' => 'Unauthorized - Token has expired'], 401);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['error' => 'Unauthorized - Invalid token'], 401);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['error' => 'Unauthorized - Unable to decode token'], 401);
        }

        // Access the id_event from the token data
        $idEvent = $tokenData['id_event'];

        // Check if the token is valid and not used
        $isValidToken = Token::where('token', $token)->whereNull('used_at')->exists();

        if (!$isValidToken) {
            return response()->json(['error' => 'Unauthorized - Invalid or used token'], 401);
        }

        // Tandai token sebagai sudah digunakan
        Token::where('token', $token)->update(['used_at' => now()]);

        // You can now use $idEvent in your middleware logic

        return $next($request);
    }
}
