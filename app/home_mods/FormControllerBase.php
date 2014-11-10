<?php

class FormControllerBase extends Phalcon\Mvc\Controller
{

    protected function initialize()
    {
        $this->assets
            ->addJs('dist/jquery/jquery-1.11.1.js');

        $this->assets
            ->addCss('dist/bootstrap/css/bootstrap.css');
    }

}
