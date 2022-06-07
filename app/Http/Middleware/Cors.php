<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Closure;

class Cors
{
    public function handle(Request $request, Closure $next)
    {
        $headers = [
            'Access-Control-Allow-Origin'   => '*',
            'Access-Control-Allow-Methods'  => 'POST, GET, PUT, PATCH, DELETE, OPTIONS',
            'Access-Control-Allow-Headers'  => 'Content-Type, Authorization, X-Page-Size, X-Requested-With, ' .
                'Content-Disposition, X-Filename, token',
            'Access-Control-Expose-Headers' => 'X-Total-Count, X-Total-Active, X-Per-Page, X-Current-Page, ' .
                'X-Count-Page, X-Filename, Content-Disposition',
        ];

        if ($request->isMethod('OPTIONS')) {
            return response()->json(['method' => 'OPTIONS'], 200, $headers);
        }

        $response = $next($request);

        foreach ($headers as $key => $value) {
            $response->headers->set($key, $value);
        }

        return $response;
    }
}
