<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserIdOwnership
{
    public function handle(Request $request, Closure $next)
    {
        $userId = $request->route('user_id');  
        if (Auth::user()->id !== (int)$userId) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return $next($request);
    }
}

