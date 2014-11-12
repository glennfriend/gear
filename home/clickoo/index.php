<?php

if ( !isset($_GET['n'])) {
    header('Content-Type: text/javascript');
    exit;
}

if ( !isset($_GET['m'])) {
    header('Content-Type: text/javascript');
    exit;
}

$dirName = preg_replace('#[^A-Za-z0-9]#','',$_GET['n']);
if ( strlen($dirName)>10 ) {
    $dirName = substr($dirName,0,10);
}
$msg = trim($_GET['m']);

if ( !$dirName || !$msg  ) {
    header('Content-Type: text/javascript');
    exit;
}


//
ob_start();
    echo date("Y-m-d H:i:s ");
    echo $msg;
    $tmpData = ob_get_contents();
ob_end_clean();
file_put_contents( 'log/'. $dirName .'.log', $tmpData."\n", FILE_APPEND );

//
header('Content-Type: text/javascript');