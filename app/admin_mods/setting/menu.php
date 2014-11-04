<?php

    $roles = array(
        'lowest'  => 'visit',
        'manager' => 'manager',
    );

    // Dashboard
    $option = array(
        'main_order' => 100,
        'main'      => array(
            'key'   => 'dashboard',
            'label' => 'Dashboard',
            'link'  => url('dashboard'),
            'role'  => $roles['lowest'],
        ),
        'sub' => array(
            array(
                'key'   => 'dashboard',
                'label' => 'Dashboard',
                'link'  => url('dashboard'),
                'role'  => $roles['lowest'],
            )
        )
    );
    MenuManager::addOption($option);

    // 系統功能
    $option = array(
        'main_order' => 900,
        'main'      => array(
            'key'   => 'system',
            'label' => 'System',
            'link'  => url('systemic/config'),
            'role'  => $roles['manager'],
        ),
        'sub' => array(
            array(
                'key'   => 'config',
                'label' => 'Config',
                'link'  => url('systemic/config'),
                'role'  => $roles['manager'],
            ),
            array(
                'key'   => 'environment',
                'label' => 'Environment',
                'link'  => url('systemic/environment'),
                'role'  => $roles['manager'],
            ),
            array(
                'key'   => 'gearman',
                'label' => 'Gearman',
                'link'  => url('systemic/gearman'),
                'role'  => $roles['manager'],
            ),
            array(
                'key'   => 'passwd',
                'label' => 'Passwd',
                'link'  => url('systemic/passwd'),
                'role'  => $roles['manager'],
            )
        )
    );
    MenuManager::addOption($option);

