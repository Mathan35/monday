<?php
namespace App\Http\Action;

use App\Helpers\General;
use TBlack\MondayAPI\Token;
use TBlack\MondayAPI\MondayBoard;

class UpdateTicketAction{

    use General;

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
         $board_id = 2570123971;
         $id_group = 'tickets';
         

 
         # For Query
         $items = General::findId($payLoad);
         logger($items);
         # For update Item
         $item_id = $items['items_by_column_values'][0]['column_values'][4]['text'];
         $column_values = [ 
             'text'    => isset($code['code']) ? $code['code'] : 'NULL',
             'description' => $payLoad['description']===null?'NULL':$payLoad['description'],
             'visibility5' => $payLoad['visibility'],
             'due_date' => $payLoad['due_date']===null?'':$payLoad['due_date'],
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
     
        }

        //update logs
        $this->logs('update ticket ( Success -> Monday)', $payLoad);

    }
}