<?php

exit;  //測試未成功


/*
    如果抓不到資料, 可以檢查:

        1. magento 是否正常運作, 並確認路徑是否正確
        2. 抓取的 cookie 名稱不正確

*/
header('Content-Type: text/html; charset=utf-8');
define('APP_PORTAL','home');
require_once '../app/init.php';




// check magento library path
$magentoInfoFile = APP_SB_SHOP_PATH . '/app/Mage.php';
if( !file_exists( $magentoInfoFile ) ) {
    header('Content-type: application/json');
    echo '{}';
    exit;
}

// include magento
require $magentoInfoFile;
umask(0);
Mage::app();
if ( defined('APP_MAGENTO_COOKIE_NAME') ) {
    Mage::getSingleton('core/session', array('name'=>APP_MAGENTO_COOKIE_NAME) );
}

// output
header('Content-type: application/json');
echo json_encode(array(
    'items_in_cart'      => (int)            Mage::helper('checkout/cart')->getCart()->getItemsCount() ,
    'customer_logged_in' => (int)            Mage::getSingleton('customer/session')->isLoggedIn(),
    'customer_id'        => (int)            Mage::getSingleton('customer/session')->getCustomer()->entity_id,
    'customer_email'     => trim(strip_tags( Mage::getSingleton('customer/session')->getCustomer()->email       )),
    'customer_name'      => trim(strip_tags( Mage::getSingleton('customer/session')->getCustomer()->lastname    )),
));

?>