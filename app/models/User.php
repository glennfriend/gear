<?php

/**
 *  User
 *
 */
class User extends BaseObject
{

    CONST STATUS_ALL    = -1;
    CONST STATUS_CLOSE  = 0;
    CONST STATUS_OPEN   = 1;
    CONST STATUS_DELETE = 9;

    /**
     *  請依照 table 正確填寫該 field 內容
     *  @return array()
     */
    public static function getTableDefinition()
    {
        /*
            變數名稱 =>
                type    格式 => 文字/數字/浮點數字/日期
                filters 過濾 => _filter_ + method
                field   欄位 => 資料庫欄位
                value   值   => 預設值
        */
        return array(
            'id' => array(
                'type'    => 'integer',
                'filters' => array('intval'),
                'storage' => 'getId',
                'field'   => 'id',
            ),
            'account' => array(
                'type'    => 'string',
                'filters' => array('strip_tags','trim'),
                'storage' => 'getAccount',
                'field'   => 'account',
            ),
            'password' => array(
                'type'    => 'string',
                'filters' => array(),
                'storage' => 'getPassword',
                'field'   => 'password',
            ),
            'roleIds' => array(
                'type'    => 'string',
                'filters' => array('strip_tags','trim'),
                'storage' => 'getRoleIds',
                'field'   => 'role_ids',
            ),
            'email' => array(
                'type'    => 'string',
                'filters' => array('strip_tags','trim'),
                'storage' => 'getEmail',
                'field'   => 'email',
            ),
            'status' => array(
                'type'    => 'integer',
                'filters' => array('intval'),
                'storage' => 'getStatus',
                'field'   => 'status',
                'value'   => self::STATUS_CLOSE,
            ),
            'createTime' => array(
                'type'    => 'timestamp',
                'filters' => array('intval'),
                'storage' => 'getCreateTime',
                'field'   => 'create_time',
                'value'   => time(),
            ),
            'updateTime' => array(
                'type'    => 'timestamp',
                'filters' => array('intval'),
                'storage' => 'getUpdateTime',
                'field'   => 'update_time',
                'value'   => time(),
            ),
            'properties' => array(
                'type'    => 'string',
                'filters' => array('arrayval'), // 特殊的 object filter, 通常會在這裡加新的 method
                'storage' => 'getProperties',
                'field'   => 'properties',
            ),
        );
    }

    /**
     *  Disabled methods
     *  @return array()
     */
    public static function getDisabledMethods()
    {
        // parent::getDisabledMethods();
        return array();
    }

    /**
     *  validate
     *  @return messages Array()
     */
    public function validate()
    {
        $messages = array();

        if( !$this->getAccount() ) {
            $messages['account'] = '該欄位必填';
        }

        // email
        $result = filter_var( $this->getEmail(), FILTER_VALIDATE_EMAIL );
        if(!$result) {
            $messages['email'] = 'Email 格式不正確';
        }

        // choose value
        $result = false;
        foreach ( cc('attribList', $this, 'status') as $name => $value ) {
            if ( $this->getStatus()==$value ) {
                $result = true;
                break;
            }
        }
        if (!$result) {
            $messages['status'] = 'status incorrect';
        }

        return $messages;
    }

    /**
     *  filter object data
     */
    public function filter()
    {
    }


    /* ------------------------------------------------------------------------------------------------------------------------
        basic method rewrite or extends
    ------------------------------------------------------------------------------------------------------------------------ */

    public function setPassword($string)
    {
        $this->store['password'] = $this->passwordEncode($string);
    }
    public function setPurePassword($string)
    {
        $this->store['password'] = $string;
    }
    /**
     *  驗証輸入的密碼是否跟物件中的密碼相同
     *  @return boolean
     */
    public function validatePassword( $password )
    {
        if( !$password || !$this->store['password'] ) {
            return false;
        }
        if( $this->passwordEncode($password) !== $this->store['password'] ) {
            return false;
        }
        return true;
    }
    /**
     *  密碼 的 加密方式
     *  @return string
     */
    protected function passwordEncode( $password )
    {
        // TODO: 建議再加上其它的保護
        return md5($password);
    }

    /**
     *  get createTime by format
     *  @param  format , date format string
     *  @return string
     */
    function getCreateTimeByFormat( $format="Y-m-d" )
    {
        return date($format,$this->getCreateTime());
    }

    /**
     *  get updateTime by format
     *  @param  format , date format string
     *  @return string
     */
    function getUpdateTimeByFormat( $format="Y-m-d" )
    {
        return date($format,$this->getUpdateTime());
    }


    /* ------------------------------------------------------------------------------------------------------------------------
        extends
    ------------------------------------------------------------------------------------------------------------------------ */

    /**
     *  get role names
     *  @return array
     */
    public function getRoleNames()
    {
        $roleInfo = $this->getProperty('roleInfo');
        $result = array();
        foreach ( $roleInfo as $role ) {
            $result[] = $role['name'];
        }
        return $result;
    }

    /**
     *  get role descriptions
     *  @return array
     */
    public function getRoleDescriptions()
    {
        $roleInfo = $this->getProperty('roleInfo');
        $result = array();
        foreach ( $roleInfo as $role ) {
            $result[] = $role['description'];
        }
        return $result;
    }

    /**
     *  檢查是否有對應到任何其中一種的權限, 只要有一種符合就會回傳 true
     *  代入值若為 `null` 或是 `空值` 則回傳 false
     *
     *  @param string array, $permissions
     *  @return boolean
     */
    public function hasPermission( $askPermissions )
    {
        if ( !is_array($askPermissions) ) {
            return false;
        }

        $roleNames = $this->getRoleNames();
        foreach ( $askPermissions as $ask ) {
            if ( !$ask ) {
                continue;
            }
            if ( in_array($ask,$roleNames) ) {
                return true;
            }
        }
        return false;

    }

}

