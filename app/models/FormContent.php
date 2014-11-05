<?php

/**
 *
 */
class FormContent extends BaseObject
{

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
            'userId' => array(
                'type'    => 'integer',
                'filters' => array('intval'),
                'storage' => 'getUserId',
                'field'   => 'user_id',
            ),
            'formPageId' => array(
                'type'    => 'integer',
                'filters' => array('intval'),
                'storage' => 'getFormPageId',
                'field'   => 'form_page_id',
            ),
            'contents' => array(
                'type'    => 'string',
                'filters' => array(),
                'storage' => 'getContents',
                'field'   => 'contents',
            ),
            'createTime' => array(
                'type'    => 'timestamp',
                'filters' => array('intval'),
                'storage' => 'getCreateTime',
                'field'   => 'create_time',
                'value'   => time(),
            ),
            'customSearch' => array(
                'type'    => 'string',
                'filters' => array('strip_tags','trim'),
                'storage' => 'getCustomSearch',
                'field'   => 'custom_search',
            ),
        );
    }

    /**
     *  filter model data
     */
    public function filter()
    {
    }

    /**
     *  validate
     *  @return messages Array()
     */
    public function validate()
    {
        return array();
    }

    /* ------------------------------------------------------------------------------------------------------------------------
        basic method
    ------------------------------------------------------------------------------------------------------------------------ */

    /**
     *  reset NormalizedSearch
     */
    public function resetNormalizedCustomSearch()
    {
        $contents = $this->getContents();
        if( !$contents ) {
            $this->store['custom_search'] = '';
            return;
        }
        $text = '';

        foreach( $contents as $key => $value ) {
            $key = trim($key);
            $value =
                Ydin\Html\Filter::javascriptTags(
                Ydin\Html\Filter::htmlTags( 
                    $value
                ));
            $text .= "[{$key}]=[{$value}] ";
        }

        $this->store['custom_search'] = $text;
    }

    /* ------------------------------------------------------------------------------------------------------------------------
        extends
    ------------------------------------------------------------------------------------------------------------------------ */

    /**
     *  get user dbobject
     *  @param isSave , is store object
     *  @return object
     */
    public function getUser( $isSave=true )
    {
        if( $this->getUserId()<=0 ) {
            return null;
        }

        if( !$isSave ) {
            $this->extend['user'] = null;
        }
        if( $this->extend['user'] ) {
            return $this->extend['user'];
        }

        $users = new Users();
        $user = $users->getUser( $this->getUserId() );

        if( $isSave ) {
            $this->extend['user'] = $user;
        }
        return $user;
    }

}

