<?php

namespace App\Enums;


enum ListTypeEnum: string
{
    case Status = "Status" ;
    case Sale = "Sale" ;
    case StatusSaleCost = "Status, Sale & Cost" ;
    case Cost = "Cost" ;
    case SaleCost = "Sale & Cost" ;
    case SaleStatus = "Sale & Status" ;
    case CostStatus = "Cost & Status" ;
    case LinkCategory = "Link Category" ;
    case UnlinkCategory = "Unlink Category" ;

}
