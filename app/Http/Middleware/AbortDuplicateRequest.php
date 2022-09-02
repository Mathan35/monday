<?php

namespace App\Http\Middleware;

use App\Models\Logs;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class AbortDuplicateRequest
{

    public function handle(Request $request, Closure $next)
    {
        $data = request()->getContent();

        if ($request->route()->getName() === 'update-ticket') {
            $logData = Logs::orderBy('created_at', 'desc')->where('title', 'update ticket ( Success -> Monday)')->where('data', $data)->exists();
            abort_if($logData, 409, 'Conflict request');
        }

        if ($request->route()->getName() === 'create-ticket') {
            abort_unless(Arr::get(json_decode($data, true), 'parent_type'), 409, 'Conflict request');
        }

        return $next($request);
    }
}
