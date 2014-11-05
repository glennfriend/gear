<?php

class SystemicController extends ControllerBase
{

    public function initialize()
    {
        parent::initialize();
        MenuManager::setMainKey('systemic');

        if( !UserManager::isAdmin() ) {
            $this->redirectMainPage();
            return;
        }
    }

    public function configAction()
    {
        MenuManager::setSubKey('config');

        $configs = new Configs();

        // update setting
        if( InputBrg::isPost() ) {

            $result = null;
            $validateInfo = Array();
            foreach( $_POST as $key => $value ) {

                $config = $configs->getConfigByKey( $key );
                if( !$config || 1!=$config->getDisplay() ) {
                    continue;
                }

                $value = InputBrg::post($key);
                $config->setValue( $value );
                $result = $configs->updateConfig( $config );

            }

            // clean cache
            CacheBrg::flush();

            // message
            FormMessageManager::addSuccessResultMessage('已儲存您的設定');
            FormMessageManager::addSuccessResultMessage('已清除所有的系統快取資料');
            $this->redirect('systemic/config');

        }

        $this->view->setVars(Array(
            'label'   => 'System Config',
            'configs' => $configs->findAllConfigs(),
        ));
    }

    public function EnvironmentAction()
    {
        MenuManager::setSubKey('environment');
        $this->view->setVars(Array(
            'label' => '系統環境',
        ));
    }

    public function GearmanAction()
    {
        MenuManager::setSubKey('gearman');
        $this->view->setVars(Array(
            'label' => 'Gearman',
        ));
    }

    /**
     *  顯示 User object 的加密結果
     */
    public function PasswdAction()
    {
        MenuManager::setSubKey('passwd');

        $result = null;
        $passwd = InputBrg::get('passwd');
        if ( $passwd ) {
            $user = new User();
            $user->setPassword( $passwd );
            $result = $user->getPassword();
        }

        $this->view->setVars(Array(
            'label'  => 'Passwd',
            'result' => $result,
        ));
    }

}
