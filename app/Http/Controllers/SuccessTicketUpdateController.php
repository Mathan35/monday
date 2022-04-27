<?php

namespace App\Http\Controllers;

use App\Helpers\General;
use App\Jobs\SuccessTickets;
use TBlack\MondayAPI\Token;
use Illuminate\Http\Request;
use TBlack\MondayAPI\MondayBoard;
use Illuminate\Queue\Events\Looping;
use Illuminate\Support\Facades\Http;

class SuccessTicketUpdateController extends Controller
{
    use General;
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        // return response()->json(request());

        $payload = request()->toArray();

        switch ($payload['event']['columnId']) {
            case "visibility5":
                $column_value = $payload['event']['value']['label']['text'];
                $column_name  = "visibility";
                break;
            case "due_date":
                $column_value = $payload['event']['value']['date'];
                $column_name  = "due_date";
                break;
            case "status56":
                $column_value = $payload['event']['value']['label']['text'];
                $column_name  = "status";
                break;
            case "type3":
                $column_value = $payload['event']['value']['label']['text'];
                $column_name  = "type";
                break;
            case "priority3":
                $column_value = $payload['event']['value']['label']['text'];
                $column_name  = "priority";
                break;    
            default:
          }

        $item = General::findItem($payload);

        logger($item);
        $ticketData = [
            'column_name'  =>  $column_name,
            'column_value' =>  $column_value,
            'id'           =>  $item['items_by_column_values'][0]['column_values'][0]['text']
        ];
        SuccessTickets::dispatch($ticketData);

        //update logs
        $this->logs('update ticket ( Monday -> Success)', $payload);

    
    }
}
