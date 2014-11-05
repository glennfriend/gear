<?php

    $roles = array(
        'lowest'  => 'visit',
        'manager' => 'manager',
    );

    // Dashboard
    MenuManager::addOption(array(
        'main' => array(
            'key'   => 'dashboard',
            'label' => 'Dashboard',
            'link'  => url('dashboard'),
            'role'  => $roles['lowest'],
        ),
        'sub' => array(
            array(
                'key'   => 'dashboard1',
                'label' => 'Dashboard by visit',
                'link'  => url('dashboard'),
                'role'  => $roles['lowest'],
            ),
            array(
                'key'   => 'dashboard2',
                'label' => 'Dashboard by manager 未設定顯示權限',
                'link'  => url('dashboard/advanced',array('display'=>'advanced')),
                'role'  => $roles['manager'],
            ),
            array(
                'key'   => 'dashboard3',
                'label' => 'Dashboard by nothing',
                'link'  => url('dashboard/nothing'),
                'role'  => '不存在的權限',
            ),
        )
    ));

    // 系統功能
    MenuManager::addOption(array(
        'main' => array(
            'key'   => 'system',
            'link'  => url('systemic/config'),
            'role'  => $roles['manager'],
        ),
        'sub' => array(
            array(
                'key'   => 'config',
                'link'  => url('systemic/config'),
                'role'  => $roles['manager'],
            ),
            array(
                'key'   => 'environment',
                'link'  => url('systemic/environment'),
                'role'  => $roles['manager'],
            ),
            array(
                'key'   => 'gearman',
                'link'  => url('systemic/gearman'),
                'role'  => $roles['manager'],
            ),
            array(
                'key'   => 'passwd',
                'link'  => url('systemic/passwd'),
                'role'  => $roles['manager'],
            )
        )
    ));

