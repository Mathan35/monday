<?php
namespace App\Helpers;

use App\Models\Logs;
use Illuminate\Support\Str;
use TBlack\MondayAPI\Token;
use TBlack\MondayAPI\MondayBoard;

trait General{

    public function findItem($payload)
    {
        $token = env('MONDAY_TOKEN');
        $MondayBoard = new MondayBoard();
        $MondayBoard->setToken(new Token($token));

        # find ticket id
        $query = '
        items_by_column_values(board_id: 2570123971,column_id: "item_id", column_value: "'.$payload['event']['pulseId'].'") {
          id
          name
          column_values {
            id
            text
            title
          }
        }';
        
        # For Query
        return $MondayBoard->customQuery( $query );
    }
    public function findId($payLoad)
    {
      $token = env('MONDAY_TOKEN');
      $MondayBoard = new MondayBoard();
      $MondayBoard->setToken(new Token($token));

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
      return $MondayBoard->customQuery( $query );
    }

    public function getUuid(){

      $uuid = Str::uuid()->toString();
      $check_id = Logs::where('id',$uuid)->first();

      if($check_id){
          $this->getUuid();
      }
      else{
          return $uuid;
      }
    }

    public function logs($title, $data){
      $log        = new Logs();
      $log->id    = $this->getUuid();
      $log->title = $title;
      $log->data  = json_encode($data);
      $log->save();
    }

}