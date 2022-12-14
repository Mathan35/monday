<?php

namespace App\Helpers;

use App\Models\Logs;
use Error;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Mockery\Undefined;
use TBlack\MondayAPI\Token;
use TBlack\MondayAPI\MondayBoard;

class General
{

  public static function findItem($payLoad)
  {
    $mondayBoard = new MondayBoard();
    $mondayBoard->setToken(new Token(config('services.monday.token')));

    # find ticket id
    $query = '
      items_by_column_values(board_id:'.config('services.monday.board_id').',column_id: "item_id", column_value: "' . Arr::get($payLoad, 'event.pulseId') . '") {
        id
        name
        column_values {
          id
          text
          title
        }
      }';

    # For Query
    $item = $mondayBoard->customQuery($query);

    $count = 1;
    if (!Arr::get($item, 'items_by_column_values.0')) {
      if($count>10){
        return;
      }
      $count++;
      sleep(40);
      self::findItem($payLoad);
    }
    return $mondayBoard->customQuery($query);
  }

  public static function findId($payLoad)
  {
    $mondayBoard = new MondayBoard();
    $mondayBoard->setToken(new Token(config('services.monday.token')));
    #finfin item id
    $query = '
         items_by_column_values(board_id: '.config('services.monday.board_id').',column_id: "id", column_value: "' . Arr::get($payLoad, 'id'). '") {
           id
           name
           column_values {
             id
             text
             title
           }
         }';


    # For Query
    $item = $mondayBoard->customQuery($query);
    $count = 1;
    if (!Arr::get($item, 'items_by_column_values.0')) {
      if($count>10){
        return;
      }
      $count++;
      sleep(40);
      self::findId($payLoad);
    }

    return $mondayBoard->customQuery($query);
  }

  public function getUuid()
  {
    $uuid = Str::uuid()->toString();
    $checkId = Logs::where('id', $uuid)->first();

    if ($checkId) {
      return $this->getUuid();
    }

    return $uuid;
  }

  public static function logs($title, $data)
  {
    $log = new Logs();
    $log->id = Str::uuid()->toString();
    $log->title = $title;
    $log->data = $data;
    $log->save();
  }
}
