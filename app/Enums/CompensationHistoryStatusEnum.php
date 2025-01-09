<?php

namespace App\Enums;


enum CompensationHistoryStatusEnum: string
{
    case STATUS_PENDIND = "1" ;
    case STATUS_Accepted = "2" ;
    case STATUS_Rejected = "3" ;
}
