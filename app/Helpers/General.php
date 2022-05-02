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
      items_by_column_values(board_id: 2570123971,column_id: "item_id", column_value: "' . Arr::get($payLoad, 'event.pulseId') . '") {
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

    if (!Arr::get($item, 'items_by_column_values')) {
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
         items_by_column_values(board_id: 2570123971,column_id: "id", column_value: "' . Arr::get($payLoad, 'id'). '") {
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

    if (!Arr::get($item, 'items_by_column_values')) {
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
    $log->id = self::getUuid();
    $log->title = $title;
    $log->data = json_encode($data);
    $log->save();
  }
}
