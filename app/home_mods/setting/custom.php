<?php

    SessionBrg::init();
    CookiesBrg::init();

    /**
     *  zend loader
     */
    $zendLoader = function()
    {
        require_once APP_BASE_PATH . '/app/vendors/Zend/Loader/StandardAutoloader.php';
        
        $loader = new Zend\Loader\StandardAutoloader(array(
            'autoregister_zf' => true,
            'namespaces' => array(
                'Ydin'    => APP_BASE_PATH . '/app/vendors/Ydin',
                'Imagine' => APP_BASE_PATH . '/app/vendors/Imagine',
            ),
        ));
        $loader->register();
    };
    $zendLoader();

