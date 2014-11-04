<?php

class ControllerBase extends Phalcon\Mvc\Controller
{

    protected function initialize()
    {
        if ( !UserIdentity::isLogin() ) {
            $this->redirect('');
        }

        RegisterManager::set('title','Gear Admin');

        $this->assets
            ->addJs('dist/jquery/jquery-1.11.1.js')
            ->addJs('dist/bootstrap/js/bootstrap.js');

        $this->assets
            ->addCss('dist/bootstrap/css/bootstrap.css');

        logBrg::backend( $this->dispatcher->getControllerName(), $this->dispatcher->getActionName() );

    }

    // This is executed before every found action
    public function beforeExecuteRoute($dispatcher)
    {
        /*
        if ($dispatcher->getActionName() == 'index') {
            $this->flash->error("hello all index");
            exit;
            //return false; ?????????
        }
        */
    }

    // Executed after every found action
    public function afterExecuteRoute($dispatcher)
    {
        
    }

    /**
     *  forword
     *  不會改變網址
     */
    protected function forward($uri)
    {
        $uriParts = explode('/', $uri);
        return $this->dispatcher->forward(
            array(
                'controller' => $uriParts[0], 
                'action'     => $uriParts[1]
            )
        );
    }

    /**
     *  recirect to main page
     *  會改變網址
     */
    protected function redirectMainPage()
    {
        $this->redirect('');
    }

    /**
     *  recirect to main page
     *  會改變網址
     */
    protected function redirect( $route )
    {
        $this->response->redirect( $route );
        // 重定向不會禁用視圖組件。因此視圖將正常顯示。你可以使用 $this->view->disable() 禁用視圖輸出。
        $this->view->disable();
        return;
    }

}
