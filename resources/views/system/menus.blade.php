@php
    $menu['Dashboard'] = [
        'permission' => ['system.dashboard'],
        'url' => route('system.dashboard'),
        'icon' => '<i class="fa fa-tachometer-alt"></i>',
        'text' => __('Dashboard'),
    ];

    $menu['User'] = [
        'permission' => [
            'system.user.index',
            'system.user.show',
            'system.user.create',
            'system.permission-group.index',
            'system.permission-group.show',
            'system.permission-group.create',
            'system.departments.index',
            'system.departments.edit',
            'system.departments.create',
        ],
        'icon' => '<i class="fa fa-users"></i>',
        'text' => __('User'),
        'sub' => [
            [
                'permission' => ['system.user.index', 'system.user.show', 'system.user.create'],
                'url' => route('system.user.index'),
                'text' => __('View'),
                'icon' => '<i class="fa fa-eye"></i>',
            ],
            [
                'permission' => [
                    'system.permission-group.index',
                    'system.permission-group.show',
                    'system.permission-group.create',
                ],
                'url' => route('system.permission-group.index'),
                'text' => __('Permission Group'),
                'icon' => '<i class="fa fa-lock"></i>',
            ],
            /*[
                'permission' => ['system.departments.index', 'system.departments.edit', 'system.departments.create'],
                'url' => route('system.departments.index'),
                'text' => __('Departments'),
                'icon' => '<i class="fa fa-solid fa-building"></i>',
            ],*/
        ],
    ];
    /*$menu['Reports'] = [
        'permission' => ['system.location-quantities','system.order.index','system.order.show','system.order.picking-packing'],
        'icon' => '<i class="fa-regular fa-file-lines"></i>',
        'text' => __('Reports'),
        'sub' => [
            [
                'permission' => ['system.location-quantities'],
                'url' => route('system.location-quantities'),
                'text' => __('Location Quantities'),
                'icon' => '<i class="fa-solid fa-database"></i>',
            ],
        ],
    ];*/

    /*$menu['Orders'] = [
        'permission' => ['system.order.index', 'system.station.overview'],
        'icon' => '<i class="fas fa-crosshairs"></i>',
        'text' => __('Order'),
        'sub' => [
            [
                'permission' => ['system.order.index'],
                'text' => __('Orders'),
                'icon' => '<i class="fa-solid fa-database"></i>',
                'url' => route('system.order.index'),
            ],

            [
                'permission' => 'system.order.picking-packing',
                'url' => route('system.order.picking-packing'),
                'text' => __('Picking & Packing'),
                'icon' => '<i class="fa fa-solid fa-building"></i>',
            ],

            [
                'permission' => ['system.order.index'],
                'text' => __('Ready to Ship'),
                'icon' => '<i class="fa fa-shipping-fast"></i>',
                'url' => route('system.order.ready-to-ship'),
            ],

            [
                'permission' => ['system.station.overview'],
                'url' => route('system.station.overview'),
                'text' => __('Shipment Summary'),
                'icon' => '<i class="fa-brands fa-artstation"></i>',
            ],
        ],
    ];*/

    $menu['Configuration'] = [
        'permission' => ['system.operator.index', 'system.station.index', 'system.station.create'
        ,'system.type.index', 'system.type.edit', 'system.type.create'
        ,'system.operator.index', 'system.operator.edit', 'system.operator.create',
        'system.bill.index', 'system.bill.edit', 'system.bill.create'
        ],
        'icon' => '<i class="fas fa-crosshairs"></i>',
        'text' => __('Configuration'),
        'sub' => [
            [
                'permission' => ['system.operator.index', 'system.operator.edit', 'system.operator.create'],
                'url' => route('system.operator.index'),
                'text' => __('Operator'),
                'icon' => '<i class="fa-solid fa-o"></i>',
            ],

            [
                'permission' => ['system.type.index', 'system.type.edit', 'system.type.create'],
                'url' => route('system.type.index'),
                'text' => __('Type'),
                'icon' => '<i class="fa-solid fa-o"></i>',
            ],

            [
                'permission' => ['system.bill.index', 'system.bill.edit', 'system.bill.create'],
                'url' => route('system.bill.index'),
                'text' => __('Bills'),
                'icon' => '<i class="fa-solid fa-o"></i>',
            ],

           /* [
                'permission' => ['system.station.index', 'system.station.create'],
                'url' => route('system.station.index'),
                'text' => __('Station'),
                'icon' => '<i class="fa-brands fa-artstation"></i>',
            ],*/
        ],
    ];

    $menu['Setting'] = [
        'permission' => ['system.activity-log.index'],
        'icon' => '<i class="fa fa-cog"></i>',
        'text' => __('Setting'),
        'sub' => [
            [
                'text' => __('System'),
                'icon' => '<i class="fa fa-solid fa-cog"></i>',
                'permission' => ['system.activity-log.index'],
                'sub' => [
                    [
                        'permission' => ['system.activity-log.index', 'system.activity-log.show'],
                        'url' => route('system.activity-log.index'),
                        'text' => __('Activity Log'),
                        'icon' => ' <i class="fa fa-solid fa-magnifying-glass"></i>',
                    ],
                ],
            ],
        ],
    ];
@endphp

@foreach ($menu as $onemenu)
    {!! generateMenu($onemenu) !!}
@endforeach
