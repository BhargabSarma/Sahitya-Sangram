<?php


namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Exceptions\PostTooLargeException;

class VerifyPostSize
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Set your max POST size in bytes (e.g., 50MB)
        $max = 50 * 1024 * 1024; // 50 MB

        $contentLength = (int) $request->server('CONTENT_LENGTH', 0);

        if ($max > 0 && $contentLength > $max) {
            throw new PostTooLargeException('The POST data is too large.');
        }

        return $next($request);
    }
}
