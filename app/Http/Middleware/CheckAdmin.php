<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // DÒNG DEBUG: Nó sẽ hiện thẳng Role của bạn lên Postman
        // dd($user->role); 

        if (!$user || !$user->isAdmin()) {
            // Tạm thời sửa message này để xem ID và Role hiện tại là gì
            return response()->json([
                'debug_info' => [
                    'id' => $user->id,
                    'role_trong_db' => $user->role,
                    'check_is_admin' => $user->isAdmin()
                ],
                'message' => 'Cảnh báo: Quyền truy cập bị từ chối!'
            ], 403);
        }

        return $next($request);
    }
}
