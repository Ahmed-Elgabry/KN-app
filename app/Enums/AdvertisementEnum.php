<?php

namespace App\Enums;

Enum AdvertisementEnum: string
{
    case DIRECT_PRODUCT = 'direct_product';
    case PERCENTAGE_ALL = 'percentage_all';
    case PERCENTAGE_SPECIFIC = 'percentage_specific';
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    case BLOCKED = 'blocked';
}
