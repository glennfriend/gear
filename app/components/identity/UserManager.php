<?php

/**
 *  管理登入之後的 User 自己本身
 *  由於資訊存於 session
 *  所以是等同管理 session 中的資訊
 */
class UserManager
{

    /**
     *  get User if authenticate
     *  @return Users model
     */
    public static function getUser()
    {
        $accountId = SessionBrg::get('account_id');
        if( !$accountId ) {
            return false;
        }

        $users = new Users();
        return $users->getUser( $accountId );
    }

    /**
     *  公開顯示 user 名稱
     *  @return string
     */
    public static function getDisplayUser()
    {
        if( UserIdentity::isLogin() ) {
            $user = self::getUser();
            return $user->getAccount();
        }
        else {
            return '[未知的使用者]';
        }
    }

    /* ================================================================================
        dev environment or not
    ================================================================================ */

    /**
     *  是否為 開發者 權限
     *  @return boolean
     */
    public static function isDeveloper()
    {
        $myself = UserManager::getUser();
        if( !$myself ) {
            return false;
        }
        return $myself->hasPermission( array('developer') );
    }

    /**
     *  是否為 網站管理者 權限
     *  @return boolean
     */
    public static function isAdmin()
    {
        $myself = UserManager::getUser();
        if( !$myself ) {
            return false;
        }
        return $myself->hasPermission( array('manager','developer') );
    }

    /**
     *  是否擁有該權限
     *  @param array, $askPermissions
     *  @return boolean
     */
    public static function hasPermission( $askPermissions )
    {
        $myself = UserManager::getUser();
        if( !$myself ) {
            return false;
        }
        return $myself->hasPermission( $askPermissions );
    }

    /**
     *  設定開發環境模式
     *  如果 role name 權限非 developer , 將無法設定成功
     *
     *  @param boolean mode
     */
    public static function setDebugMode( $mode )
    {
        if( !UserManager::isAdmin() ) {
            return;
        }

        if( true===$mode ) {
            SessionBrg::set('is_debug',true);
        }
        else {
            SessionBrg::set('is_debug',false);
        }
    }

    /**
     *  是否為除錯模式
     *  @return boolean
     */
    public static function isDebugMode()
    {
        if( true === SessionBrg::get('is_debug') ) {
            return true;
        }
        return false;
    }

    /* ================================================================================

    ================================================================================ */

}

