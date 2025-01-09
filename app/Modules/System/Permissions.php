<?php

return [



    [
        'group_title' => __('Users'),
        'name' => __('User'),
        'permissions' => [
            'view-all-user' => ['system.user.index','system.user.show', 'system.get-user-activity-log'],
            'create-user' => ['system.user.create', 'system.user.store'],
            'update-user' => ['system.user.edit', 'system.user.update']
        ]
    ],


    [
        'name' => __('Permission Group'),
        'permissions' => [
            'view-all-permission-groups' => ['system.permission-group.index'],
            'create-permission-group' => ['system.permission-group.create', 'system.permission-group.store'],
            'update-permission-group' => ['system.permission-group.edit', 'system.permission-group.update']
        ]
    ],
    [
        'name' => __('User Departments'),
        'permissions' => [
            'view-user-department' => ['system.departments.index'],
            'create-user-department' => ['system.departments.create', 'system.departments.store'],
            'update-user-department' => ['system.departments.edit', 'system.departments.update']
        ]
    ],


    [
        'name' => __('Auth Sessions'),
        'permissions' => [
            'view-auth-session' => ['system.auth-sessions.index', 'system.get-auth-session', 'system.auth-sessions.show'],
            'delete-auth-session' => ['system.auth-sessions.destroy'],
            'view-log-viewer'=>[ 'log-viewer.index'],
        ]
    ],



    [
        'group_title' => __('Operator'),
        'name' => __('operator'),
        'permissions' => [
            'view-operators' => ['system.operator.index'],
            'create-operator' => ['system.operator.create','system.operator.store'],
            'edit-operator' => ['system.operator.edit','system.operator.update'],
            'delete-operator' => ['system.operator.destroy'],
        ]
    ],

    [
        'group_title' => __('Type'),
        'name' => __('Type'),
        'permissions' => [
            'view-types' => ['system.type.index'],
            'create-type' => ['system.type.create','system.type.store'],
            'edit-type' => ['system.type.edit','system.type.update'],
            'delete-type' => ['system.type.destroy'],
        ]
    ],

    [
        'group_title' => __('Bill'),
        'name' => __('Bill'),
        'permissions' => [
            'view-bills' => ['system.bill.index'],
            'create-bill' => ['system.bill.create','system.bill.store'],
            'edit-bill' => ['system.bill.edit','system.bill.update'],
            'delete-bill' => ['system.bill.destroy'],
        ]
    ],


    [
        'name' => __('Activity Log'),
        'permissions' => [
            'view-activity-log'=>['system.activity-log.index','system.activity-log.show'],
            'view-log-viewer'=>[ 'log-viewer.index'],
            'git-version-control'=>[ 'system.git-version-control'],
            'documentation'=>[ 'system.documentation'],
        ]
    ],
];
