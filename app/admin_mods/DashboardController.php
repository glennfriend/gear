<?php

class DashboardController extends ControllerBase
{

    public function initialize()
    {
        parent::initialize();
        MenuManager::setMainKey('dashboard');
    }

    public function indexAction()
    {
        MenuManager::setSubKey('dashboard1');
    }

    public function advancedAction()
    {
        MenuManager::setSubKey('dashboard2');
    }

    public function nothingAction()
    {
        if( !UserManager::hasPermission('role-沒有這個權限') ) {
            FormMessageManager::addErrorResultMessage('Insufficient Permissions');
            $this->redirectMainPage();
            return;
        }
        MenuManager::setSubKey('dashboard3');
    }

}