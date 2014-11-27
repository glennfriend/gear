<?php
/*
    傳送 messages 至系統管理, 決定分派到何處
    目前該程式只 push ot hipChat

        - 參數
            pwd     密碼
            msg     內容
            from    發送者
            room    頻道

        - post
            https://domain/pwd=xxx

        - get 
            file_get_contents("https://domain/pwd=xxx&msg=xxx");
      
        - command line curl
            curl -X POST "https://domain/messages.php?pwd=xxx&msg=222"
            curl -X POST "https://domain/messages.php?pwd=xxx" -d msg="333"
            curl -X POST "https://domain/messages.php?pwd=xxx" -d msg="$(cat abc)"
            curl -sS https://domain/messages.php -d "pwd=xxx" -d msg="333" -d from="chris"

        - php curl
            $url = "https://domain/";
            $fields = http_build_query(array(
                'pwd' => "xxx",
                'msg' => urlencode("xxx"),
            ));

            // connection
            $ch = curl_init();
                curl_setopt( $ch, CURLOPT_URL, $url );
                curl_setopt( $ch, CURLOPT_POST, true );
                curl_setopt( $ch, CURLOPT_POSTFIELDS, $fields );
                curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
                $result = curl_exec($ch);
            curl_close($ch);

*/
$password = 'Zces_2gWS$@';
$args = getParameter();

if ( $args['pwd'] !== $password ) {
    exit;
}

if ( !$args['msg'] ) {
    exit;
}


//
header('Content-Type: text/html; charset=utf-8');
define('APP_PORTAL','home');
require_once '../app/init.php';


try {
    $app = $factoryApplication();
} catch( \Phalcon\Exception $e ) {
    echo "PhalconException: ", $e->getMessage();
    echo '<p>';
    echo    nl2br(htmlentities( $e->getTraceAsString() ));
    echo '</p>';
    exit;
}

//
run();
exit;

/**
 *  
 */
function run()
{
    $args = getParameter();
    InfoBrg::hipChatByMessagesApi( $args['msg'], $args['room'], $args['from'] );
}

/**
 *  取得所有參數
 */
function getParameter()
{
    $args = array(
        'pwd'   => getValue('pwd'),
        'room'  => getValue('room') ?: 'stargazer',
        'from'  => getValue('from') ?: 'API',
        'msg'   => getValue('msg'),
    );

    // https or http
    if ( isset($_SERVER['HTTPS']) && 'on'===$_SERVER['HTTPS'] ) {
        //
    }
    else {
        $args['from'] .= ' (http)';
    }

    // echo '<pre>'; print_r( $args ); echo "</pre>\n";
    return $args;
}

/**
 *  取得參數
 */
function getValue( $key, $filter=true )
{
    if ( isset($_POST[$key]) ) {
        return trim(strip_tags($_POST[$key]));
    }
    elseif ( isset($_GET[$key]) ) {
        return trim(strip_tags($_GET[$key]));
    }
    return null;
}

