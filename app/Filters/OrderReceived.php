<?php

namespace App\Filters;

use Carbon\Carbon;
use Closure;

class OrderReceived extends Filter
{
    public function applyFilter($builder)
    {
        // Get the current time
        switch (request($this->filterName())) {
            case 'all':
                return $builder;
                break;
            case 'past_12':
                // Get the time 12 hours ago
                return $builder->where('order.order_date', '>=',  now()->subHours(12));
                break;
            case 'past_24':
                return $builder->where('order.order_date', '>=', now()->subHours(24));
                break;
            case 'today':
                // Get today's date
                return $builder->whereDate('order.order_date', Carbon::today());
                break;
            case 'yesterday':
                return $builder->whereDate('order.order_date', Carbon::yesterday());
                break;
            case 'last_2':
                return $builder->whereBetween('order.order_date', [now()->subDays(2), now()]);
                break;
            case 'last_3':
                return $builder->whereBetween('order.order_date', [now()->subDays(3), now()]);
                break;
            case 'last_5':
                return $builder->whereBetween('order.order_date', [now()->subDays(5), now()]);
                break;
            case 'last_7':

                return $builder->whereBetween('order.order_date', [now()->subDays(7), now()]);
                break;
            case 'last_15':

                return $builder->whereBetween('order.order_date', [now()->subDays(15), now()]);
                break;
            case 'last_30':
                return $builder->whereBetween('order.order_date', [now()->subDays(30), now()]);
                break;
            case 'current_month':
                return $builder->whereYear('order.order_date', now()->year)
                ->whereMonth('order.order_date', now()->month);

                break;
            case 'last_month':
                // Get the last month and year
                return $builder->whereYear('order.order_date', now()->subMonth()->year)
                ->whereMonth('order.order_date', now()->subMonth()->month);
                break;
            case 'this_year':
                // Get the current year
                return $builder->whereYear('order.order_date', now()->year);

                break;
            
            default:
            return $builder;
                break;
        }
    }
}
