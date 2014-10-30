<?php

/**
 * Admin UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity
{
    /**
     *  error code
     *  -1  not validate
     *   1  account empty
     *   2  password empty
     *   0  success
     */
    protected static $errorCode = -1;

    /**
     * Authenticates a user.
     * @return boolean whether authentication succeeds.
     */
    public static function authenticate( $account, $password )
    {
        if( !isset($account) ) {
            self::$errorCode = 1;
            return false;
        }

        if( !isset($password) ) {
            self::$errorCode = 2;
            return false;
        }

        $users = new Users();
        $user = $users->getUserByAuthenticate( $account, $password );
        if( !$user ) {
            return false;
        }

        self::$errorCode = 0;

        // setting basic config
        SessionBrg::set('account_id', $user->getId() );
        SessionBrg::set(
            'login_user_info_string',
            $user->getId() .'_'. $_SERVER['REMOTE_ADDR'] .'_'. $_SERVER['HTTP_USER_AGENT']
        );

        // custom setting
        // 注意, 以下的程式要放在此 method 最後面
        // 依照情況設定
        if( UserManager::isAdmin() ) {
            UserManager::setDebugMode(true);
        }
        return true;
    }

    /**
     * get error code
     * @return int
     */
    public static function getErrorCode()
    {
        return self::$errorCode;
    }

    /**
     * check is login
     * @return boolean
     */
    public static function isLogin()
    {
        $accountId = SessionBrg::get('account_id');
        if( !$accountId ) {
            return false;
        }
        return true;
    }

    /**
     * destory session
     */
    public static function logout()
    {
        SessionBrg::destroy();
    }

}

