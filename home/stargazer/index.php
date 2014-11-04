<?php
header('Content-Type: text/html; charset=utf-8');
define('APP_PORTAL','admin');
require_once '../../app/init.php';


try {
    $app = $factoryApplication();
    echo $app->handle()->getContent();
} catch( \Phalcon\Exception $e ) {
    echo "PhalconException: ", $e->getMessage();
    echo '<br />';
    echo nl2br(htmlentities( $e->getTraceAsString() ));
}

//