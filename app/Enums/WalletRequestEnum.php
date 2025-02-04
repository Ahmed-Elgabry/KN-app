<?php

namespace App\Enums;

enum WalletRequestEnum: string
{
    case STATUS_PENDIND = "pending" ;
    case STATUS_Accepted = "approved" ;
    case STATUS_Rejected = "rejected" ;
    case STATUS_Blocked = "blocked" ;
}
