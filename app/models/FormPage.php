<?php

/**
 *
 */
class FormPage extends BaseObject
{
    CONST STATUS_ALL        = -1;
    CONST STATUS_CLOSE      = 0;
    CONST STATUS_OPEN       = 1;
    CONST STATUS_DELETE     = 9;

    /**
     *  請依照 table 正確填寫該 field 內容
     *  @return array()
     */
    public static function getTableDefinition()
    {
        return array(
            'id' => array(
                'type'    => 'integer',
                'filters' => array('intval'),
                'storage' => 'getId',
                'field'   => 'id',
            ),
            'name' => array(
                'type'    => 'string',
                'filters' => array('strip_tags','trim'),
                'storage' => 'getName',
                'field'   => 'name',
            ),
            'keyword' => array(
                'type'    => 'string',
                'filters' => array('strip_tags','trim'),
                'storage' => 'getKeyword',
                'field'   => 'keyword',
            ),
            'status' => array(
                'type'    => 'integer',
                'filters' => array('intval'),
                'storage' => 'getStatus',
                'field'   => 'status',
                'value'   => self::STATUS_OPEN,
            ),
            'timeStart' => array(
                'type'    => 'timestamp',
                'filters' => array('intval'),
                'storage' => 'getTimeStart',
                'field'   => 'time_start',
                'value'   => strtotime('1980-01-01'),
            ),
            'timeEnd' => array(
                'type'    => 'timestamp',
                'filters' => array('intval'),
                'storage' => 'getTimeEnd',
                'field'   => 'time_end',
                'value'   => strtotime('1980-01-01'),
            ),
            'createTime' => array(
                'type'    => 'timestamp',
                'filters' => array('intval'),
                'storage' => 'getCreateTime',
                'field'   => 'create_time',
                'value'   => strtotime('1980-01-01'),
            ),
            'properties' => array(
                'type'    => 'string',
                'filters' => array('arrayval'),
                'storage' => 'getProperties',
                'field'   => 'properties',
            ),
        );
    }

    /**
     *  validate
     *  @return messages Array()
     */
    public function validate()
    {
        $messages = Array();

        if( !$this->getName() ) {
            $messages['name'] = '該欄位必填';
        }

        // choose value
        $result = false;
        foreach( cc('attribList', $this, 'status') as $name => $value ) {
            if( $this->getStatus()==$value ) {
                $result = true;
                break;
            }
        }
        if(!$result) {
            $messages['status'] = 'status 不正確';
        }

        // timestamp
        if( $this->getCreateTime() < -28800 ) {
            $messages['createTime'] = '日期不正確';
        }

        return $messages;
    }

    /**
     *  filter model data
     */
    public function filter()
    {
    }

    /* ------------------------------------------------------------------------------------------------------------------------
        basic method
    ------------------------------------------------------------------------------------------------------------------------ */


    /* ------------------------------------------------------------------------------------------------------------------------
        extends
    ------------------------------------------------------------------------------------------------------------------------ */

    /**
     *  表單活動狀態
     *  0 = 表單停用
     *  1 = 表單啟用, 在活動中
     *  2 = 表單啟用, 活動還未開始
     *  3 = 表單啟用, 活動已結束
     *
     *  @return int
     */
    public function getActiveStatus()
    {
        if( $this->store['status'] !== 1 ) {
            return 0;
        }

        $now = time();
        if( $now < $this->getTimeStart() ) {
            return 2;
        }
        if( $now >= $this->getTimeEnd() ) {
            return 3;
        }

        return 1;
    }

}

