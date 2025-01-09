<?php

namespace App\Enums;


enum OrderStatusEnum: string
{
    case AwaitingFulfillment = "1" ;
    case Picking = "2" ;
    case Packing = "3" ;
    case ReadyToShip = "4" ;

    public static function values(): array
    {
        return array_column(self::cases(), 'value','name');
    }

    public static function values_lang(): array
    {
        $data = [];
        foreach (self::cases() as  $row){
            $data[$row->value] =  __($row->name) ;
        }
        return $data;
    }

}
