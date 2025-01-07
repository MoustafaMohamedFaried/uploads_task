<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Http;

class CheckUserToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Send the request to validate the token
        $response = Http::withHeaders([
            'x-api-key' => env('API_KEY'),
            'Authorization' => $request->header('Authorization'),
        ])->get('http://127.0.0.1:8000/api/user_profile');

        // If the response is not successful, block the request
        if (!$response->successful()) {
            return response()->json([
                'status' => false,
                'message' => $response->json('message') ?? 'Token validation failed',
                'code' => $response->status(),
            ], $response->status());
        }

        // Attach the user profile to the request for use in the controller
        $request->attributes->set('userProfile', $response->json());

        // Allow the request to proceed
        return $next($request);
    }
}
