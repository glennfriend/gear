<?php

/**
 *  檢查是否有進入該程式的權限
 */
function checkSecurity()
{
    $password = isset($_GET['password'])  ?  $_GET['password']  :  '';
    if ( '2000' !== $password ) {
        die('empty');
    }
}

/**
 *  檢查是否執行該程式, 或者只是觀察, 而非執行
 */
function getisExec()
{
    $isExec = isset($_GET['isExec'])  ?  $_GET['isExec']  :  '';
    if ( 'yes' === $isExec ) {
        return true;
    }
    return false;
}

/**
 *  取得真正執行 script 的 url
 */
function getExecUrl()
{
    //_SERVER["REQUEST_URI"]
    //_SERVER["SCRIPT_NAME"]
    //_SERVER["PHP_SELF"]
    return '//'. $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] . '&isExec=yes';
}

/**
 *
 */
function outputHtmlHeader( $isExec )
{
    echo '<meta http-equiv="Content-Language" content="zh-tw" />';
    echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
    
    if ( $isExec ) {
        echo '<ul>';
        echo    '<li style="font-size:18px; color:#900; ">程式執行中 (請勿中斷程式!)</li>';
        echo '</ul>';
    }
    else {
        $url = getExecUrl();
        echo <<<EOD
            <ul>
                <li style="font-size:18px; color:#090;">測試指令中</li>
                <li style="font-size:18px; color:#090;">執行前, 請檢查 schema 是否已加到線上的資料表?</li>
                <li>
                    <input type="button" value="真正執行這個程式" onclick="document.location.href='{$url}'">
                </li>
            </ul>
EOD;
    }

}



