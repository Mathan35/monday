<?php
namespace App\Http\Action;

use App\Helpers\General;
use Illuminate\Support\Arr;
use TBlack\MondayAPI\Token;
use TBlack\MondayAPI\MondayBoard;
use Illuminate\Support\Facades\Http;
use Monolog\Logger;

class CreateTicketAction{

    public function create()
    {
        //recieving payload from success webhook
        $payLoad = json_decode(request()->getContent(), true);

        //getting ticket code
        $code = $payLoad['linked_ticket'];
        $status = $payLoad['status'];
        $type = $payLoad['type'];
        $priority = $payLoad['priority'];
        $project = $payLoad['parent'];

        # Insert new Item on Board
        $column_values = [
            'name' => Arr::get($payLoad, 'title'),
            'id' => Arr::get($payLoad, 'id'),
            'code' => Arr::get($code, 'code'),
            'description' => Arr::get($payLoad, 'description'),
            'visibility' => Arr::get($payLoad, 'visibility'),
            'due_date' => Arr::get($payLoad, 'due_data'),
            'status' => Arr::get($status, 'value'),
            'priority' => $priority['value'],
            'type' => Arr::get($type, 'value'),
            'project' => Arr::get($project, 'title'),
        ];


        $token = env('MONDAY_TOKEN');
        $apiUrl = 'https://api.monday.com/v2';
        $headers = ['Content-Type: application/json', 'Authorization: ' . $token];
    
    
        $query ='mutation {
            create_item (board_id: 2645712561, group_id: "topics", item_name: "'.$payLoad['title'].'", column_values: "'.addslashes(json_encode($column_values)).'"){
                id,
            }
            
        }';
    
        $data = @file_get_contents($apiUrl, false, stream_context_create([
          'http' => [
            'method' => 'POST',
            'header' => $headers,
            'content' => json_encode(['query' => $query]),
        ]
        ]));
    
        $responseContent = json_decode($data, true);


        //update logs
        General::logs('create ticket ( Success -> Monday)', $payLoad);
    }
}
