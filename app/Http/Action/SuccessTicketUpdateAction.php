<?php
namespace App\Http\Action;

use App\Helpers\General;
use Illuminate\Support\Arr;
use App\Jobs\SuccessTickets;
use Illuminate\Support\Str;

class SuccessTicketUpdateAction {

    public function update($payload)
    {
        $item = General::findItem($payload);

        $ticketData = [
            'column_name' => $payload['event']['columnId']==='due_date2' ? 'due_date' : $payload['event']['columnId'],
            'column_value' => Arr::get($payload,'event.columnId')==='due_date2'?Arr::get($payload,'event.value.date') : Arr::get($payload,'event.value.label.text'),
            'id' => $item['items_by_column_values'][0]['column_values'][0]['text'],
            'name' => Arr::get($payload,'event.pulseName', Str::random(10)),
        ];

        SuccessTickets::dispatch($ticketData);

        //update logs
        General::logs('update ticket ( Monday -> Success)', $payload);

        return true;
    }
}
