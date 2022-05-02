<?php
namespace App\Http\Action;

use App\Helpers\General;
use Illuminate\Support\Arr;
use TBlack\MondayAPI\Token;
use TBlack\MondayAPI\MondayBoard;
use Illuminate\Support\Facades\Http;

class CreateTicketAction{

    public function create(){
        //recieving payload from success webhook
        $payLoad = json_decode(request()->getContent(), true);

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
        $board_id = config('services.monday.board_id');
        $id_group = 'tickets';
        $column_values = [
            'ticket_id' => $payLoad['id'],
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
            $addResult = $MondayBoard
                        ->on($board_id)
                        ->group($id_group)
                        ->addItem( $payLoad['title'], $column_values);
        }

        //update logs
        General::logs('create ticket ( Success -> Monday)', $payLoad);
    }
}
