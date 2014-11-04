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
                'key'   => 'board1',
                'label' => 'board1 by visit 未設定無限寬度',
                'link'  => url('dashboard'),
                'role'  => $roles['lowest'],
            ),
            array(
                'key'   => 'board2',
                'label' => 'board2 by manager 未設定顯示權限',
                'link'  => url('dashboard',array('display'=>'advanced')),
                'role'  => $roles['manager'],
            )
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

