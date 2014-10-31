<?php
/**
 *  Log Bridge
 */
class LogBrg
{

    /**
     *  dispatcher
     */
    private static $logPath = null;

    /**
     *
     */
    public static function init( $logPath='' )
    {
        if ( $logPath ) {
            self::$logPath = $logPath;
        }
    }

    /**
     *  developer log
     */
    public static function custom( $content )
    {
        $content = date("Y-m-d H:i:s") .' - ' . $content;
        self::write( 'custom.log', $content );
    }

    /**
     *  error log
     */
    public static function error( $content )
    {
        $content = date("Y-m-d H:i:s") .' - '. $_SERVER['REMOTE_ADDR'] . ' - '. $content;
        /*
            $content = 'post '.    print_r($_POST, true)    . $content;
            $content = 'session '. print_r($_SESSION, true) . $content;
        */
        self::write( 'error.log', $content );
    }

    /**
     *  access log
     */
    public static function access( $content )
    {
        $content = date("Y-m-d H:i:s") .' - '. $_SERVER['REMOTE_ADDR'] . ' - '. $content;
        self::write( 'access.log', $content );
    }

    /**
     *  frontend log 
     */
    public static function frontend( $controller, $action )
    {
        $content = date("Y-m-d H:i:s")
            .' - '
            . $_SERVER['REMOTE_ADDR']
            . ' - '
            . $controller
            . '/'
            . $action
            . ' - '
            . $_SERVER['REQUEST_URI'];

        self::write( 'frontend.log', $content );
    }

    /**
     *  backend log 
     */
    public static function backend()
    {
        $content = date("Y-m-d H:i:s")
            .' - '
            . $_SERVER['REMOTE_ADDR']
            . ' - '
            . $_SERVER['REQUEST_URI'];

        self::write( 'backend.log', $content );
    }

    /**
     *  monitor log
     */
    public static function monitor( $content )
    {
        $content = date("Y-m-d H:i:s") .' - '. $content;
        self::write( 'monitor.log', $content );
    }

    /* --------------------------------------------------------------------------------
        private
    -------------------------------------------------------------------------------- */

    /**
     *  write file
     */
    public static function write( $file, $content )
    {
        // TODO: please validate file name, only a-z & one dot(.) & no double dot (..)
        // .......
    
        $filename = self::$logPath .'/'. $file;
        file_put_contents( $filename, $content."\n", FILE_APPEND );

    }

}

