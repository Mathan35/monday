<?php
namespace App\Http\Action;

use App\Helpers\General;
use TBlack\MondayAPI\Token;
use TBlack\MondayAPI\MondayBoard;

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
         $item_id = $items['items_by_column_values'][0]['column_values'][3]['text'];
         $column_values = [ 
            'ticket_id' => $payLoad['id'],
            'code' => isset($code['code']) ? $code['code'] : 'NULL',
            'text1' => $payLoad['description']===null?'NULL':$payLoad['description'],
            'visibility' => $payLoad['visibility'],
            'due_date' => $payLoad['due_date']===null?'':$payLoad['due_date'],
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
     
        }

        //update logs
        General::logs('update ticket ( Success -> Monday)', $payLoad);

    }
}