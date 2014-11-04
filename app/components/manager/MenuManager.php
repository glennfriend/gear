<?php
/*
    example:

        MenuManager::addOptions( array(......) );
        MenuManager::addOptions( array(......) );
        MenuManager::addOptions( array(......) );

        // about main menu
        foreach ( MenuManager::getOrderMenu() as $menu ) {


        }

        // about sub menu
        $menu = MenuManager::getFocusMenu();
        foreach ( $menu['sub'] as $subMenu ) {
            
        }
*/



/**
 *  plugin base
 *
 *  initOrder 為執行 plugin 的順序值
 *      from 1 to 999
 *  
 *  menuOrder 為從由而右放置的順序值
 *      from 1 to 999
 *  
 *  
 *  
 *  
 *  
 */
class MenuManager
{

    /**
     *
     */
    static protected $_options = array();

    /**
     *
     */
    static protected $_mainFocus = null;

    /**
     *
     */
    static protected $_subFocus = null;

    /**
     *  add option
     *      - 如果 main key 相同, 則後面會覆蓋前面的設定
     *
     *  @return boolean
     */
    static public function addOption( $option )
    {
        $option += array(
            'main_order' => 200,
            'main'       => array(),
            'sub'        => array()
        );

        $key = null;
        if ( isset($option['main']['key']) ) {
            $key = $option['main']['key'];
        }

        if ( !$key ) {
            return false;
        }

        self::$_options[$key] = $option;
        return true;
    }

    /**
     *
     */
    static public function getMainFocus()
    {
        return self::$_mainFocus;
    }

    /**
     *  設定現在在那一個 main menu
     *
     *  @return boolean
     */
    static public function setMainFocus( $key )
    {
        $menu = self::getMenu($key);
        if ( !$menu ) {
            return false;
        }
        self::$_mainFocus = $key;
        return true;
    }

    /**
     *
     */
    static public function getSubFocus()
    {
        return self::$_subFocus;
    }

    /**
     *  設定現在在那一個 sub menu
     *      - 該程式會 validate
     *
     *  @return boolean
     */
    static public function setSubFocus( $subFocus )
    {
        $mainFocus = self::getMainFocus();
        if ( !$mainFocus ) {
            return false;
        }

        if ( !isset(self::$_options[$mainFocus]) ) {
            return false;
        }
        $option = self::$_options[$mainFocus];

        foreach ( $option['sub'] as $index => $sub ) {
            if ( $sub['key'] === $subFocus ) {
                self::$_subFocus = $subFocus;
                return true;
            }
        }

        return false;
    }

    /* --------------------------------------------------------------------------------
        extends
    -------------------------------------------------------------------------------- */

    /**
     *  get menu
     *  取得 focus 的 main menu
     *
     *  @return array or null
     */
    static public function getMenu( $key )
    {
        if ( $key && isset(self::$_options[$key]) ) {
            return self::$_options[$key];
        }
        return null;
    }

    /**
     *  取得 focus 的 main menu
     *
     *  @return array or false
     */
    static public function getFocusMain()
    {
        $mainFocus = self::getMainFocus();
        return self::getMenu( $mainFocus );
    }

    /**
     *  取得 focus 的 sub menu
     *
     *  @return array or false
     */
    static public function getSub()
    {
        $mainFocus = self::getMainFocus();
        $subFocus  = self::getSubFocus();
        if ( !$mainFocus || !$subFocus ) {
            return false;
        }

        $menu = self::getMenu( $mainFocus );
        foreach ( $menu['sub'] as $sub ) {
            if ( $sub['key'] === $subFocus ) {
                return $sub;
            }
        }
        return false;
    }

    /**
     *  取得排序過後的 menu
     */
    static public function getOrderMenu()
    {
        // 未排序
        return self::$_options;
    }


}