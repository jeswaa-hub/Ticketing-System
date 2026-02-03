<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class EmployeeMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check() || Auth::user()->user_type !== 'employee') {
            abort(403);
        }

        $userId = Auth::id();
        if ($userId) {
            DB::table('users')->where('id', $userId)->update([
                'last_seen_at' => now(),
            ]);

            DB::table('employee_activity_logs')->insert([
                'user_id' => $userId,
                'method' => $request->method(),
                'path' => '/' . ltrim($request->path(), '/'),
                'created_at' => now(),
            ]);
        }

        return $next($request);
    }
}
