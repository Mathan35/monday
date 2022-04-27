<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SuccessTicketStoreController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
            
        $payload = request()->toArray();

        $response = Http::withoutVerifying()->post('https://local-api.success.test/v1/acmeinc/public/monday', $payload);

    }
}
