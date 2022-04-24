<?php

namespace App\Http\Controllers;

use App\Http\Action\UpdateTicketAction;
use TBlack\MondayAPI\Token;
use Illuminate\Http\Request;
use TBlack\MondayAPI\MondayBoard;

class UpdateTicketController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(UpdateTicketAction $query)
    {
       return $query->update();

    }
}
