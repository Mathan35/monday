<?php

namespace App\Http\Controllers;

use App\Http\Action\CreateTicketAction;
use TBlack\MondayAPI\Token;
use Illuminate\Http\Request;
use TBlack\MondayAPI\MondayBoard;

class CreateTicketController extends Controller
{
    
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(CreateTicketAction $query)
    {
        
        return $query->create();
    }
}
