<?php

class DashboardController extends ControllerBase
{

    public function initialize()
    {
        parent::initialize();
        MenuManager::setMainKey('dashboard');
        MenuManager::setSubKey('board1');
    }

    public function indexAction()
    {
    }

}