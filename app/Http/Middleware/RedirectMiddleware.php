<?php

namespace App\Http\Middleware;

use App\Models\Redirect;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class RedirectMiddleware
{
    /**
     * Handle an incoming request and apply 301/302 redirects if matching rule exists.
     */
    public function handle(Request $request, Closure $next)
    {
        // Only run for GET/HEAD requests and avoid admin routes
        if ($request->isMethod('GET') && !$request->is('admin*')) {
            $path = '/' . ltrim($request->path(), '/');

            // Quick check if redirects table exists
            try {
                $redirect = Redirect::where('old_url', $path)
                    ->where('is_active', true)
                    ->first();

                if ($redirect) {
                    $targetUrl = trim($redirect->new_url);
                    // Block malicious pseudo-protocols (e.g. javascript:, data:, vbscript:)
                    if (preg_match('#^\s*(javascript|data|vbscript)\s*:#i', $targetUrl)) {
                        return $next($request);
                    }
                    // Increment hits counter asynchronously or silently
                    $redirect->increment('hits');
                    return redirect($targetUrl, (int) $redirect->redirect_type);
                }
            } catch (\Exception $e) {
                // Table might not exist yet or connection error, silently proceed
            }
        }

        return $next($request);
    }
}
