<?php

class IndexController extends ControllerBase
{

    public function indexAction()
    {
        // $this->view->setVars();
    }

    public function clearCartAction()
    {
        MageCart::clear();

        $this->redirect('');
    }



}