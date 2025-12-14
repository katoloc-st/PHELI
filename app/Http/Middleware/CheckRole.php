<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!Auth::check()) {
            // Nếu là AJAX request, trả về 401
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
            return redirect()->route('login');
        }

        // Kiểm tra xem user có role trong danh sách roles được phép không
        if (!in_array(Auth::user()->role, $roles)) {
            // Nếu là AJAX request, trả về 403
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['message' => 'Bạn không có quyền truy cập.'], 403);
            }
            abort(403, 'Bạn không có quyền truy cập trang này.');
        }

        return $next($request);
    }
}
