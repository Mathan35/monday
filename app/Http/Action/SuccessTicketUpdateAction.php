<?php

namespace App\Http\Action;

use App\Helpers\General;
use Illuminate\Support\Arr;
use App\Jobs\SuccessTickets;
use Illuminate\Support\Str;

class SuccessTicketUpdateAction
{

    private $payload;

    public function update($payload)
    {
<<<<<<< HEAD

        $item = General::findItem($payload);
        $ticketData = [
            'column_name' => $payload['event']['columnId'],
            'column_value' => Arr::get($payload,'event.columnId')==='due_date'?Arr::get($payload,'event.value.date') : Arr::get($payload,'event.value.label.text'),
            'id' => $item['items_by_column_values'][0]['column_values'][4]['text'],
            'name' => Arr::get($payload,'event.pulseName', Str::random(10)),
        ];
        
=======
        $item = General::findItem($payload);
        $this->payload = $payload;

        if (Arr::get($payload, 'event.type') == 'update_column_value') {
            $ticketData = [
                'column_name' => $this->getColumnName($payload['event']['columnId']),
                'column_value' => $this->getColumnValue($payload['event']['columnId']), //Arr::get($payload, 'event.columnId') === 'due_date2' ? Arr::get($payload, 'event.value.date') : Arr::get($payload, 'event.value.label.text'),
                'id' => $item['items_by_column_values'][0]['column_values'][0]['text'],
                'name' => Arr::get($payload, 'event.pulseName', Str::random(10)),
                'type' => 'update_column_value'
            ];
        }

        if (Arr::get($payload, 'event.type') == 'update_name') {
            $ticketData = [
                'id' => $item['items_by_column_values'][0]['column_values'][0]['text'],
                'name' => Arr::get($payload, 'event.value.name'),
                'type' => 'update_name'
            ];
        }

>>>>>>> 9fad4a3310e9e7588588e6f06f5fe107919e9353
        SuccessTickets::dispatch($ticketData);

        //update logs
        General::logs('update ticket ( Monday -> Success)', $payload);

        return true;
    }

    private function getColumnName($columnId)
    {
        return [
            $columnId => $columnId,
            'due_date2' => 'due_date',
            'text1' => 'description',
        ][$columnId];
    }

    private function getColumnValue($columnId)
    {
        return [
            $columnId =>  Arr::get($this->payload, 'event.value.label.text'),
            'due_date2' => Arr::get($this->payload, 'event.value.date'),
            'text1' => Arr::get($this->payload, 'event.value.value'),
        ][$columnId];
    }
}
