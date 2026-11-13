<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class ThrottleEnquiry
{
    public function handle(Request $request, Closure $next): Response
    {
        $key = 'throttle_enquiry_' . $request->ip();
        $maxAttempts = 5;
        $decayMinutes = 15;

        $attempts = Cache::get($key, 0);

        if ($attempts >= $maxAttempts) {
            return back()
                ->withErrors(['mobile' => 'Too many enquiry submissions. Please try again later.'])
                ->withInput();
        }

        Cache::increment($key);
        Cache::put($key, $attempts + 1, now()->addMinutes($decayMinutes));

        return $next($request);
    }
}
