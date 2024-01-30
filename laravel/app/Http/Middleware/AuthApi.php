<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthApi
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
        // $payload = array(
        //     "exp" => time() + (60 * 60 * 24 * 365),  // Hết hạn sau 1 năm
        //     "id" => 1,
        // );

        // $jwt = JWT::encode($payload, env('KEY'), 'HS256');
        // dd($jwt);
        $jwt = $request->header('Authorization');
        if (!$jwt || !ENV('KEY')) {
            return response()->json('Authorization not found', 401);
        }
        $decode = JWT::decode($jwt, new Key(ENV('KEY'), 'HS256'));
        $user = User::find($decode->id);
        if (!$user)
            return response()->json('User not found', 401);
        Auth::login($user);
        return $next($request);
    }
}
