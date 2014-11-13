<?php

class Users extends ZendModel
{
    const CACHE_USER = 'cache_user';

    /**
     *  table name
     */
    protected $tableName = 'users';

    /**
     *  get method
     */
    protected $getMethod = 'getUser';

    /**
     *  covert db row to object
     *  return object
     */
    public function mapRow( $row )
    {
        $object = new User();
        $object->setId           ( $row['id']                      );
        $object->setAccount      ( $row['account']                 );
        $object->setPurePassword ( $row['password']                );
        $object->setEmail        ( $row['email']                   );
        $object->setStatus       ( $row['status']                  );
        $object->setRoleIds      ( $row['role_ids']                );
        $object->setCreateTime   ( strtotime($row['create_time'])  );
        $object->setUpdateTime   ( strtotime($row['update_time'])  );
        $object->setProperties   ( $row['properties'] );

        // user extended info
        $roleInfo = $this->getRoleInfo( $object->getRoleIds() );
        $object->setProperty('roleInfo',$roleInfo);

        return $object;
    }

    /**
     *  add user
     */
    public function addUser( $object )
    {
        $insertId = $this->addObject( $object, true );
        if ( !$insertId ) {
            return false;
        }

        $object = $this->getUser( $insertId );
        if ( !$object ) {
            return false;
        }

        $this->preChangeHook( $object );
        return $insertId;
    }

    /**
     *  update user
     */
    public function updateUser( $object )
    {
        $result = $this->updateObject( $object );
        if ( !$result ) {
            return false;
        }

        $this->preChangeHook( $object );
        return $result;
    }

    /**
     *  disable user
     */
    public function disableUser( $object )
    {
        $object->setStatus( User::STATUS_DELETE );
        return $this->updateObject( $object );
    }

    /**
     *  pre change hook, first remove cache, second do something more
     *  about add, update, delete
     *  @param object
     */
    public function preChangeHook( $object )
    {
        // first, remove cache
        $this->removeCache( $object );
    }


    /**
     *  remove cache
     *  @param object
     */
    protected function removeCache( $object )
    {
        if ( $object->getId() <= 0 ) {
            return;
        }
        $cacheKey = $this->getFullCacheKey( $object->getId(), Users::CACHE_USER );
        CacheBrg::remove( $cacheKey );
    }

    /* ================================================================================
        access database
    ================================================================================ */

    /**
     *  get by Authenticate
     *  認證的部份必須包含狀態的檢查
     *  @return object or empty array
     */
    public function getUserByAuthenticate( $account, $password )
    {
        $select = $this->getDbSelect();
        $select->where(array( 'account'  => $account        ));
        $select->where(array( 'password' => md5($password)  ));

        $objects = $this->findObjects( $select, array(
            '_page' => 1,
            '_itemsPerPage' => 1,
        ));
        if ( !$objects || count($objects)<1 ) {
            return array();
        }
        $object = $objects[0];

        if( User::STATUS_OPEN !== $object->getStatus() ) {
            return array();
        }
        return $object;
    }

    /**
     *  get by id
     *  @return object or false
     */
    public function getUser( $id )
    {
        $object = $this->getObject( 'id', $id, Users::CACHE_USER );
        if ( !$object ) {
            return false;
        }
        return $object;
    }

    /**
     *  get by account
     *  @return object or empty array
     */
    public function getUserByAccount( $account )
    {
        $select = $this->getDbSelect();
        $select->where(array( 'account' => $account ));

        $objects = $this->findObjects( $select, array(
            '_page' => 1,
            '_itemsPerPage' => 1,
        ));
        if ( !$objects || count($objects)<1 ) {
            return array();
        }
        $object = $objects[0];
        return $object;
    }

    /**
     *  get user role info
     *      雖然 user_roles 沒有 cache
     *      但是其實資料會儲存在 user object 之中
     *      所以實際上有 cache
     *
     *  ※所以當權限表改變之後, 所有 user 的權限必須要更新才行!!
     *
     *  @return rows array or empty array
     */
    public function getRoleInfo( $roleIds )
    {
        if ( !$roleIds ) {
            return array();
        }

        $integerRoleIds = array();
        foreach ( explode(',',$roleIds) as $roleId ) {
            $integerRoleIds[] = (int) $roleId;
        }
        $integerRoleIds = array_unique($integerRoleIds);

        $select = $this->getDbSelect(false);
        $select->columns(array('*'));
        $select->from('user_roles');
        $select->where->in( 'id', $integerRoleIds );

        $results = $this->query($select);
        if( !$results ) {
            return array();
        }

        $rows = array();
        while( $row = $results->next() ) {
            $rows[] = $row;
        }
        return $rows;
    }

    /* ================================================================================
        find Users and get count
    ================================================================================ */

}

