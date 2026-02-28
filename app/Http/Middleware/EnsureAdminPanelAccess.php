<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureAdminPanelAccess
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        $allowed = [
            'urusetia',
            'penyemak',
            'pelulus',
            'admin',
            'pengurusan-atasan',
        ];

        if ($user && $user->hasAnyRole($allowed)) {
            return $next($request);
        }

        abort(403);
    }
}
