<?php

class CookiesBrg
{

    /**
     *  store cookie object
     */
    private static $cookie = array();

    /**
     *  cookie init
     */
    public static function init()
    {
        self::$cookie = new Phalcon\Http\Response\Cookies();
        self::$cookie->useEncryption(false);
    }

    /**
     *  get cookie
     */
    public static function get($key)
    {
        $value = self::$cookie->get( $key );
        if ( !$value ) {
            return null;
        }
        return $value;
    }

    /**
     *  set cookie
     *
     *  注意 cookie 的特性, 在設定完的那一次, 無法取得該 cookie 值
     *  除非你回寫值到 $_COOKIE 之中
     */
    public static function set($key, $value, $option=array() )
    {
        if ( is_array($option) ) {
            if( $option['expire'] ) {
                self::$cookie->set( $key, $value, time() + $option['expire'] );
            }
            /*
            elseif( $option['expire'] && $option['path'] ) {
                self::$cookies->set( $key, $value, time() + $option['expire'], $option['path'] );
            }
            elseif( $option['expire'] && $option['path'] && $option['domain'] ) {
                self::$cookies->set( $key, $value, time() + $option['expire'], $option['path'], $option['domain'] );
            }
            */
        }

        self::$cookie->set( $key, $value );
    }

    /**
     *
     */
    public static function remove($key)
    {
        self::$cookie->delete( $key );
    }

}
