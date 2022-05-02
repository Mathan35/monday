<?php
namespace App\Http\Action;

use App\Helpers\General;
use Illuminate\Support\Arr;
use App\Jobs\SuccessTickets;

class SuccessTicketUpdateAction{

    public function update($payload)
    {
        $item = General::findItem($payload);

        $ticketData = [
            'column_name' => $payload['event']['columnId'],
            'column_value' => Arr::get($payload,'event.columnId')==='due_data'?Arr::get($payload,'event.value.date') : Arr::get($payload,'event.value.label.text'),
            'id' => $item['items_by_column_values'][0]['column_values'][0]['text']
        ];
        
        SuccessTickets::dispatch($ticketData);

        //update logs
        General::logs('update ticket ( Monday -> Success)', $payload);

        return true;
    }
}