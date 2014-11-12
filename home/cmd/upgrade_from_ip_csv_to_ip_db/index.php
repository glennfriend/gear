<?php

exit;

/**
 *  從 ip2location.com 網站上下載的 ip location csv 檔案
 *  解析之後丟到資料庫的程式片段
 *
 *  匯入之後可以將一些內容做手動的修正
 *  若要修正, 請注意資料的 完整性 與 一至性
 *  例如
 *      region_name from "t'ai-wan" to "tai-wan"
 *  
 *  查詢 ip 的 sql sample
 *      SELECT * FROM `storage_city_ips` WHERE inet_aton('118.163.151.13') BETWEEN ip_from AND ip_to
 *  
 *  
 */
header('Content-Type: text/html; charset=utf-8');
define('APP_PORTAL','home');
require_once '../../../app/init.php';


try {
    $app = $factoryApplication();
} catch( \Phalcon\Exception $e ) {
    echo "PhalconException: ", $e->getMessage();
    echo '<p>';
    echo    nl2br(htmlentities( $e->getTraceAsString() ));
    echo '</p>';
    exit;
}


// ================================================================================
//  start
// ================================================================================
include_once('helper.php');
checkSecurity();
$isExec = getisExec();
outputHtmlHeader( $isExec );
set_time_limit (1 * 3600);  // one hour

echo "<pre>";
echo "\n----[ ". date("Y-m-d H:i:s (w)") ." ]-------------------------------------------------\n\n";
perform( $isExec );


/* --------------------------------------------------------------------------------
    perform
-------------------------------------------------------------------------------- */

/**
 *  取出所有 statistic_unit_logs 沒有 country 的 ip
 *  讀取 Storage_ips 裡面的 ip 資料庫
 *      有找到 -> update statistic_unit_logs
 *      沒找到 -> insert Storage_ips empty ip info
 *
 */
function perform( $isExec )
{
    /*
    $csv = 'IP2LOCATION-LITE-DB11.CSV';
    $allowImportCountries = array(
        'us', 'uk', 'ca', 'au', 'sa', 'za', 'tw'
    );
    */
    $allowImportCountries = array(
        'us',
    );
    $csv = 'test.csv';

    if (($handle = fopen($csv, 'r')) !== false) {

        // get the first row, which contains the column-titles (if necessary)
        $header = fgetcsv($handle);

        $count = 0;
        while (($line = fgetcsv($handle)) !== false) {

            // 將資料進行整理, 並加入 key 成為 key/value 格式的 array
            $item = array(
                'ip_from'       => (double) $line[0],
                'ip_to'         => (double) $line[1],
                'country_code'  => addslashes(strtolower(trim(strip_tags($line[2])))),
                'country_name'  => addslashes(strtolower(trim(strip_tags($line[3])))),
                'region_name'   => addslashes(strtolower(trim(strip_tags($line[4])))),
                'city_name'     => addslashes(strtolower(trim(strip_tags($line[5])))),
                'latitude'      => (float) $line[6],
                'longitude'     => (float) $line[7],
                'zip_code'      => addslashes(strtolower(trim(strip_tags($line[8])))),
                'time_zone'     => addslashes(strtolower(trim(strip_tags($line[9])))),
            );

            // 如果量太大中斷的話, 你可能會想從某一個 ip 開始匯入
            //if ( $item['ip_from'] < 2147483647 ) {
            //    continue;
            //}

            if (
                !in_array( $item['country_code'], $allowImportCountries ) ||
                '-' == $item['country_code'] || 
                '-' == $item['region_name'] || 
                '-' == $item['city_name'] 
            ) {
                continue;
            }

            $sql = <<<EOD
                REPLACE INTO storage_city_ips(
                    `ip_from`,
                    `ip_to`,
                    `country_code`,
                    `region_name`,
                    `city_name`
                )VALUES(
                    '{$item['ip_from']}',
                    '{$item['ip_to']}',
                    '{$item['country_code']}',
                    '{$item['region_name']}',
                    '{$item['city_name']}'
                );
EOD;

            /*
                echo '<pre style="background-color:#def;color:#000;text-align:left;font-size:10px;font-family:dina,GulimChe;">';
                print_r( $line );
                print_r( $item );
                print_r( $sql );
                echo "</pre>\n";
                exit;
            */

            $count++;

            $affect = null;
            if( $isExec ) {
                $model = new ZendModel();
                $adapter = $model->getAdapter();
                $results = $adapter->query( $sql )->execute();
                $affect = $results->count();
            }
            else {
                if( $count > 30 ) {
                    break;
                }
            }

            $ipFrom = long2ip($item['ip_from']);
            $ipTo   = long2ip($item['ip_to']);
            if ( $affect>=1 ) {
                echo "affect {$affect} = {$ipFrom} to {$ipTo}\n";
            }
            else {
                echo "fail = {$ipFrom} to {$ipTo}\n";
            }
            ob_flush();
            flush();

        }
        unset($ipFrom,$ipTo,$item,$line);
        fclose($handle);
    }

    echo "\n--------------------------------------------------------------------------------\n\n";
    echo '合計 <b>'.$count."</b> 筆資料\n";

}
