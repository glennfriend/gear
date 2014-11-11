<?php

class MeController extends ControllerBase
{

    public function beforeExecuteRoute()
    {
        parent::beforeExecuteRoute();
        $this->user = UserManager::getUser();
    }

    public function indexAction()
    {
        $password    = InputBrg::get('password');
        $password2   = InputBrg::get('password2');
        $oldPassword = InputBrg::get('oldPassword');
        $view        = 'password';

        // update password only
        if ( InputBrg::isPost() ) {

            if ( strlen($password) < 6 ) {
                FormMessageManager::addFieldMessage(array('password'=>'新的密碼必須在 6 個字元以上'));
                FormMessageManager::addErrorResultMessage('新的密碼錯誤');
                $this->render($view);
                return true;
            }
            if ($password !== $password2) {
                FormMessageManager::addFieldMessage(array('password' =>'請重新輸入'));
                FormMessageManager::addFieldMessage(array('password2'=>'請重新輸入'));
                FormMessageManager::addErrorResultMessage('您輸入的兩組新密碼並不相同, 請重新輸入');
                $this->render($view);
                return true;
            }

            $user = $this->user;
            if ( !$user->validatePassword($oldPassword) ) {
                FormMessageManager::addFieldMessage(array('oldPassword'=>'請重新輸入'));
                FormMessageManager::addErrorResultMessage('舊密碼輸入錯誤');
                $this->render($view);
                return true;
            }

            $user->setPassword( $password );
            $user->filter();

            if ( $fieldMessages = $user->validate() ) {
                FormMessageManager::setFieldMessages( $fieldMessages );
                FormMessageManager::addErrorResultMessage();
            }
            else {
                $users = new Users();
                $users->updateUser($user);
                FormMessageManager::addSuccessResultMessage('修改成功');
                $this->redirect('me');
            }

        } // if

        $this->view->setVars(Array(
            'user' => $this->user,
        ));
    }

    /**
     *  修改密碼
     */
    public function passwordAction()
    {
        $password    = InputBrg::get('password');
        $password2   = InputBrg::get('password2');
        $oldPassword = InputBrg::get('oldPassword');
        $view        = 'password';

        $this->view->setVars(Array(
            'user' => $this->user,
        ));

        // update password only
        if ( InputBrg::isPost() ) {

            if ( strlen($password) < 6 ) {
                FormMessageManager::addFieldMessage(array('password'=>'新的密碼必須在 6 個字元以上'));
                FormMessageManager::addErrorResultMessage('新的密碼錯誤');
                return;
            }
            if ($password !== $password2) {
                FormMessageManager::addFieldMessage(array('password' =>'請重新輸入'));
                FormMessageManager::addFieldMessage(array('password2'=>'請重新輸入'));
                FormMessageManager::addErrorResultMessage('您輸入的兩組新密碼並不相同, 請重新輸入');
                return;
            }

            $user = $this->user;
            if ( !$user->validatePassword($oldPassword) ) {
                FormMessageManager::addFieldMessage(array('oldPassword'=>'請重新輸入'));
                FormMessageManager::addErrorResultMessage('舊密碼輸入錯誤');
                return;
            }

            $user->setPassword( $password );
            $user->filter();

            if ( $fieldMessages = $user->validate() ) {
                FormMessageManager::setFieldMessages( $fieldMessages );
                FormMessageManager::addErrorResultMessage();
            }
            else {
                $users = new Users();
                $users->updateUser($user);
                FormMessageManager::addSuccessResultMessage('修改成功');
                $this->redirect('me');
                return;
            }

        }

    }

    /**
     *  change user current album
     */
    public function changeEnvironmentAction()
    {
        $isDeveloper = InputBrg::get('isDeveloper');
        if ($isDeveloper) {
            UserManager::setDebugMode(true);
        }
        else {
            UserManager::setDebugMode(false);
        }
        $this->redirect('me');
    }

}
