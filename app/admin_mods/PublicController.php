<?php

class PublicController extends ControllerBase
{

    public function initialize()
    {
        parent::initialize();

        // disabled layout, use action view
        $this->view->setRenderLevel(\Phalcon\Mvc\View::LEVEL_LAYOUT);
    }

    public function indexAction()
    {
        $this->loginAction();
    }

    public function loginAction()
    {
        $userIdentity = new UserIdentity();
        if( $userIdentity->isLogin() ) {
            $this->redirect( $this->createUrl('/dashboard') );
            exit;
        }

        $account  = trim(strip_tags( InputBrg::get('account') ));
        $password = InputBrg::get('password');

        if( InputBrg::isPost() ) {

            if( $userIdentity->authenticate( $account, $password ) ) {
                // 登入成功
                $this->redirect( $this->createUrl('/dashboard') );
            }
            else {
                // 帳號或密碼錯誤
                FormMessageManager::addErrorResultMessage('The password you entered is invalid. Check the field highlighted below and try again.');
            }
        }

        $this->view->setVars(Array(
            'account' => $account,
        ));
    }

    /**
     *
     */
    public function logoutAction()
    {
        UserIdentity::logout();
        $this->redirect('/');
    }


}

?>