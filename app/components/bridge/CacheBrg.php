<?php

/**
 *  bridge phalcon Cache
 */
class CacheBrg
{

    /**
     *  cache
     */
    private static $cache = array();

    /**
     *  session init
     */
    public static function init( $cachePath )
    {
        //self::$cache = $di->getCache();

        // Frontend: 主要負責檢查KEY是否過期
        // 以及 在存儲到backend之前 / 從backend取數據之后執行額外的數據轉換
        $frontCache = new Phalcon\Cache\Frontend\Data(array(
            "lifetime" => APP_CACHE_LIFETIME
        ));

        // Backend: 主要負責溝通, 并根據前端的需求讀寫數據。
        $cache = new Phalcon\Cache\Backend\File($frontCache, array(
            "cacheDir" => $cachePath
        ));

        /*
        $cache = new Phalcon\Cache\Backend\Memcached($frontCache, array(
            "host" => "localhost",
            "port" => "11211"
        ));
        */

        self::$cache = $cache;

    }

    /* --------------------------------------------------------------------------------
        access
    -------------------------------------------------------------------------------- */

    /**
     *  get cache
     */
    public static function get( $key )
    {
        return self::$cache->get( $key );
    }

    /*
    public static function has( $key )
    {
        if ($cache->exists($key) ) {
            return true;
        }
        return false;
    }
    */

    /* --------------------------------------------------------------------------------
        write
    -------------------------------------------------------------------------------- */

    /**
     *  set cache
     */
    public static function set( $key, $value )
    {
        self::$cache->save( $key, $value );
    }

    // public static function forever 無時間限制的快取

    /**
     *  remove cache
     */
    public static function remove( $key )
    {
        self::$cache->delete( $key );
    }

    // public static function removePrefix 移除該值開頭的所有快取

    /**
     *  clean all cache data
     */
    public static function flush()
    {
        $keys = self::$cache->queryKeys();
        foreach ( $keys as $key ) {
            self::$cache->delete($key);
        }
    }

}

/*
    phalcon 提供了以 prefix 提取 keys 的方法如下
        $keys = $cache->queryKeys("my-prefix");
*/