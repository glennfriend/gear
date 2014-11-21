<?php

class SessionBrg
{

    /**
     *  store di
     */
    private static $di;

    /**
     *  session init
     */
    public static function init( $di )
    {
        session_save_path( APP_BASE_PATH . '/var/session' );

        $session = new Phalcon\Session\Adapter\Files(array(
            'uniqueId' => APP_PRIVATE_DYNAMIC_CODE
        ));
        $session->start();

        $di->set('session', $session);
        self::$di = $di;
    }

    /* --------------------------------------------------------------------------------
        access
    -------------------------------------------------------------------------------- */

    /**
     *  get session
     */
    public static function get( $key, $defaultValue=null )
    {
        $val = self::$di->get('session')->get($key);
        if ( !$val && $defaultValue ) {
            return $defaultValue;
        }
        return $val;
    }

    /* --------------------------------------------------------------------------------
        write
    -------------------------------------------------------------------------------- */

    /**
     *  set
     */
    public static function set( $key, $value )
    {
        return self::$di->get('session')->set( $key, $value );
    }

    /**
     *  remove
     */
    public static function remove( $key )
    {
        self::$di->get('session')->remove( $key );
    }

    /**
     *  destroy all
     */
    public static function destroy()
    {
        self::$di->get('session')->destroy();
    }

}
