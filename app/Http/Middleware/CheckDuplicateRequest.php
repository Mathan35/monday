<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Logs;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;

class CheckDuplicateRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        $data = request()->getContent();

        if($request->route()->getName()==='update-ticket')
        {
            $logData = Logs::orderBy('created_at', 'desc')->where('title','update ticket ( Success -> Monday)')->where('data',$data)->exists();
            abort_if($logData,403);
        }

        if($request->route()->getName()==='create-ticket')
        {
            abort_unless(Arr::get(json_decode($data, true),'parent_type'), 403);
        }
        
        return $next($request);

    }
}
