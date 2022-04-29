<?php
namespace App\Helpers;

use App\Models\Logs;
use Error;
use Illuminate\Support\Str;
use Mockery\Undefined;
use TBlack\MondayAPI\Token;
use TBlack\MondayAPI\MondayBoard;

class General{

    public static function findItem($payLoad)
    {

      $token       = env('MONDAY_TOKEN');
      $MondayBoard = new MondayBoard();
      $MondayBoard->setToken(new Token($token));

      # find ticket id
      $query = '
      items_by_column_values(board_id: 2570123971,column_id: "item_id", column_value: "'.$payLoad['event']['pulseId'].'") {
        id
        name
        column_values {
          id
          text
          title
        }
      }';
      
      # For Query
      $item =  $MondayBoard->customQuery( $query );

      if(sizeof($item['items_by_column_values'])===0)
      {
        logger('running');
        self::findItem($payLoad);

      }
      return $MondayBoard->customQuery( $query );
    }

    public static function findId($payLoad)
    {
      $token       = env('MONDAY_TOKEN');
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
      $item =  $MondayBoard->customQuery( $query );

      if(sizeof($item['items_by_column_values'])===0)
      {
        self::findId($payLoad);
      }
      return $MondayBoard->customQuery( $query );
    }

    public function getUuid()
    {

      $uuid     = Str::uuid()->toString();
      $check_id = Logs::where('id',$uuid)->first();

      if($check_id){
          $this->getUuid();
      }
      else{
          return $uuid;
      }
    }

    public static function logs($title, $data){
      $log        = new Logs();
      $log->id    = self::getUuid();
      $log->title = $title;
      $log->data  = json_encode($data);
      $log->save();
    }

}