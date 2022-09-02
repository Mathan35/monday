<?php

namespace App\Http\Action;

use App\Helpers\General;
use Illuminate\Support\Arr;
use TBlack\MondayAPI\Token;
use TBlack\MondayAPI\MondayBoard;

class UpdateTicketAction
{

    public function update($payLoad)
    {
        //getting ticket code
        $code = $payLoad['linked_ticket'];
        $status = $payLoad['status'];
        $type = $payLoad['type'];
        $priority = $payLoad['priority'];
        $project = $payLoad['parent'];

        # For Query
        $items = General::findId($payLoad);
        
        # For update Item
        $item_id = Arr::get($items, 'items_by_column_values.0.id');
        $columnValues = [
            'name' => Arr::get($payLoad, 'title'),
            'id' => Arr::get($payLoad, 'id'),
            'code' => Arr::get($code, 'code'),
            'description' => Arr::get($payLoad, 'description'),
            'visibility' => Arr::get($payLoad, 'visibility'),
            'due_date' => Arr::get($payLoad, 'due_date'),
            'status' => Arr::get($status, 'value'),
            'priority' => $priority['value'],
            'type' => Arr::get($type, 'value'),
            'project' => Arr::get($project, 'title'),
        ];

        $token = env('MONDAY_TOKEN');
        $apiUrl = 'https://api.monday.com/v2';
        $headers = ['Content-Type: application/json', 'Authorization: ' . $token];

        if ($payLoad['parent_type'] != NULL) {

            $query = 'mutation {
                change_multiple_column_values(item_id: ' . $item_id . ', board_id: ' . config('services.monday.board_id') . ', column_values: "' . addslashes(json_encode($columnValues)) . '") {
                  id
                }
              }';

            $data = @file_get_contents($apiUrl, false, stream_context_create([
                'http' => [
                    'method' => 'POST',
                    'header' => $headers,
                    'content' => json_encode(['query' => $query]),
                ]
            ]));
            // logger($columnValues);
            // logger($data);
        }

        $responseContent = json_decode($data, true);

        //update logs
        General::logs('update ticket ( Success -> Monday)', $payLoad);
    }
}
