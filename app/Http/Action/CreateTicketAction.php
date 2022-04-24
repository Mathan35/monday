<?php
namespace App\Http\Action;
use TBlack\MondayAPI\Token;
use TBlack\MondayAPI\MondayBoard;

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
              $board_id = 2570123971;
              $id_group = 'tickets';
              $column_values = [ 
                'id'    => $payLoad['id'],
                'text'    => isset($code['code']) ? $code['code'] : 'NULL',
                'text_1'  => $payLoad['title'],
                'description' => $payLoad['description']===null?'NULL':$payLoad['description'],
                'visibility5' => $payLoad['visibility'],
                'due_date' => $payLoad['due_date']===null?'NULL':$payLoad['due_date'],
                'status56'  =>   $status['value'] ,
                'priority3'    =>  $priority['value'],
                'type3'    =>  $type['value'],
                'text40'    =>  isset($project['title']) ? $project['title'] : 'NULL',
            ];
              
              if($payLoad['parent_type'] != NULL){
                  $addResult = $MondayBoard
                              ->on($board_id)
                              ->group($id_group)
                              ->addItem( $payLoad['title'], $column_values);
      
                  if($addResult){
                      logger($addResult);
                  }
                  else
                      logger('failed to create');
              }


    }
}