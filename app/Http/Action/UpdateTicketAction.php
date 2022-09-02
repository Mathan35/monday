<?php

namespace App\Http\Action;

use App\Helpers\General;
use Illuminate\Support\Arr;
use TBlack\MondayAPI\Token;
use TBlack\MondayAPI\MondayBoard;

<<<<<<< HEAD
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
=======
class UpdateTicketAction{

    public function update($payLoad){

         //getting ticket code
         $code = $payLoad['linked_ticket'];
         $status = $payLoad['status'];
         $type = $payLoad['type'];
         $priority = $payLoad['priority'];
         $project = $payLoad['parent'];

         $token = env('MONDAY_TOKEN');
         $MondayBoard = new MondayBoard();
         $MondayBoard->setToken(new Token($token));

         # Insert new Item on Board
         $id_group = 'tickets';

         # For Query
         $items = General::findId($payLoad);

         # For update Item
         $item_id = $items['items_by_column_values'][0]['id'];
         $column_values = [
            'ticket_id' => $payLoad['id'],
            'name' => $payLoad['title'],
            'code' => isset($code['code']) ? $code['code'] : 'NULL',
            'text1' => $payLoad['description']===null?'NULL':$payLoad['description'],
            'visibility' => $payLoad['visibility'],
            'due_date2' => $payLoad['due_date']===null?'':$payLoad['due_date'],
            'status' => $status['value'] ,
            'priority' => $priority['value'],
            'type' => $type['value'],
            'text4' => isset($project['title']) ? $project['title'] : 'NULL',
         ];

         if($payLoad['parent_type'] != NULL){

             $updateResult = $MondayBoard
                            ->on(config('services.monday.board_id'))
                            ->group($id_group)
                            ->changeMultipleColumnValues($item_id, $column_values );

>>>>>>> 9fad4a3310e9e7588588e6f06f5fe107919e9353
        }

        $responseContent = json_decode($data, true);

        //update logs
        General::logs('update ticket ( Success -> Monday)', $payLoad);
    }
}
