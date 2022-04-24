<?php
namespace App\Http\Action;

use TBlack\MondayAPI\Token;
use TBlack\MondayAPI\MondayBoard;

class UpdateTicketAction{

    public function update(){
         #recieving payload from success webhook
         $payLoad = json_decode(request()->getContent(), true);
         // logger($payLoad);
 
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
         $board_id = 2570123971;
         $id_group = 'tickets';
         
         #finfin item id
         $query = '
         items_by_column_values(board_id: 2570123971,column_id: "id", column_value: "'.$payLoad['id'].'") {
           id
           name
           column_values {
             id
             text
             title
           }
         }';
 
         # For Query
         $items = $MondayBoard->customQuery( $query );
         # For update Item
         $item_id = $items['items_by_column_values'][0]['column_values'][3]['text'];
         $column_values = [ 
             'text'    => isset($code['code']) ? $code['code'] : 'NULL',
             'description' => $payLoad['description']===null?'NULL':$payLoad['description'],
             'visibility5' => $payLoad['visibility'],
             'due_date' => $payLoad['due_date']===null?'NULL':$payLoad['due_date'],
             'status56'  =>   $status['value'] ,
             'priority3'    =>  $priority['value'],
             'type3'    =>  $type['value'],
             'text40'    =>  isset($project['title']) ? $project['title'] : 'NULL',
         ];

         if($payLoad['parent_type'] != NULL){
 
             $updateResult = $MondayBoard
                            ->on($board_id)
                            ->group($id_group)
                            ->changeMultipleColumnValues($item_id, $column_values );
             if($updateResult){
                 logger('success');
             }
             else
                 logger('failed to update');
        }

    }
}