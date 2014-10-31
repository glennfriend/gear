<?php

    foreach ( $this->data as $data )
    {
        $type    = $data['type'];
        $class   = $data['class'];
        $alert   = $data['alert'];
        $message = $this->tag($data['messages'],'div');
        echo <<<EOD
                <div class="alert {$class}">
                    <h4>{$alert}</h4>
                    <div>{$message}</div>
                </div>
EOD;
    }

