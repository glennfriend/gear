<?php

class SessionBrg
{

    /**
     *  store session object
     */
    private static $session = array();

    /**
     *  session init
     */
    public static function init()
    {
        session_save_path( APP_BASE_PATH . '/var/session' );

        /*
        // cookie name ????
        // session_name(APP_SHOP_COOKIE_NAME);

        // 每次進來都重新變更 life time
        if ( isset( $_COOKIE[APP_SHOP_COOKIE_NAME] ) ) {
            setcookie(
                APP_SHOP_COOKIE_NAME,
                session_id( $_COOKIE[APP_SHOP_COOKIE_NAME] ),
                time() + APP_LOGIN_LIFETIME,
                "/"
            );
        }
        */

        $session = new Phalcon\Session\Adapter\Files(array(
            'uniqueId' => APP_PRIVATE_DYNAMIC_CODE
        ));
        $session->start();

        self::$session = $session;
    }

    /* --------------------------------------------------------------------------------
        access
    -------------------------------------------------------------------------------- */

    /**
     *  get session
     */
    public static function get( $key, $defaultValue=null )
    {
        $val = self::$session->get($key);
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
        return self::$session->set( $key, $value );
    }

    /**
     *  remove
     */
    public static function remove( $key )
    {
        self::$session->remove( $key );
    }

    /**
     *  destroy all
     */
    public static function destroy()
    {
        self::$session->destroy();
    }

}
