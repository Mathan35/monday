<?php

namespace App\Http\Controllers;

use App\Helpers\General;
use App\Http\Action\SuccessTicketUpdateAction;
use App\Jobs\SuccessTickets;
use TBlack\MondayAPI\Token;
use Illuminate\Http\Request;
use TBlack\MondayAPI\MondayBoard;
use Illuminate\Queue\Events\Looping;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class  SuccessTicketUpdateController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, SuccessTicketUpdateAction $query)
    {
        if($request->challenge){
            return response()->json(request());
        }

        return $query->update(request()->toArray());
    }
}
