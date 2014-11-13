<?php

class DashboardController extends ControllerBase
{

    public function beforeExecuteRoute()
    {
        parent::beforeExecuteRoute();
        MenuManager::setMainKey('dashboard');
    }

    public function indexAction()
    {
        MenuManager::setSubKey('basic');
    }

    public function advancedAction()
    {
        MenuManager::setSubKey('advanced');
    }

    public function developerAction()
    {
        MenuManager::setSubKey('developer');
    }


}