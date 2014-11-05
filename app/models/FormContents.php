<?php

/**
 *
 */
class FormContents extends ZendModel
{
    const CACHE_FORM_CONTENT = 'cache_form_content';

    /**
     *  table name
     */
    protected $tableName = 'form_contents';

    /**
     *  get method
     */
    protected $getMethod = 'getFormContent';

    /**
     *  get db object by record
     *  @param  row
     *  @return TahScan object
     */
    public function mapRow( $row )
    {
        $object = new FormContent();
        $object->setId         ( $row['id']                     );
        $object->setUserId     ( $row['user_id']                );
        $object->setFormPageId ( $row['form_page_id']           );
        $object->setContents   ( unserialize($row['contents'])  );
        $object->setCreateTime ( strtotime($row['create_time']) );
        return $object;
    }

    /* ================================================================================
        write database
    ================================================================================ */

    /**
     *  add FormContent
     *  @param FormContent object
     *  @return boolean or insert id by integer
     */
    public function addFormContent( $object )
    {
        $object->resetNormalizedCustomSearch();

        $result = $this->addObject( $object );
        if( !$result ) {
            return false;
        }

        $this->preChangeHook( $object );
        return $result;
    }

    /**
     *  update FormContent
     *  @param FormContent object
     *  @return int
     */
    public function updateFormContent( $object )
    {
        $object->resetNormalizedCustomSearch();

        $result = $this->updateObject( $object );
        if( !$result ) {
            return false;
        }
        $this->preChangeHook( $object );
        return $result;
    }

    /**
     *  delete FormContent
     *  @param int id
     *  @return boolean
     */
    public function deleteFormContent( $id )
    {
        $object = $this->getFormContent($id);
        if( !$object || ! $this->deleteObject($id) ) {
            return false;
        }
        $this->preChangeHook( $object );
        return true;
    }

    /**
     *  pre change hook, remove cache, do something more
     *  about add, update, delete
     *  @param object
     */
    public function preChangeHook( $object )
    {
        $this->removeCache( $object );
    }

    /**
     *  remove cache
     *  @param object
     */
    protected function removeCache( $object )
    {
        if( $object->getId() >= 1 ) {
            $cacheKey = $this->getFullCacheKey( $object->getId(), FormContents::CACHE_FORM_CONTENT );
            CacheBrg::remove( $cacheKey );
        }
    }


    /* ================================================================================
        access database
    ================================================================================ */

    /**
     *  get FormContent by id
     *  @param  int id
     *  @return object or empty array
     */
    public function getFormContent( $id, $userId=-1 )
    {
        $object = $this->getObject( 'id', $id, FormContents::CACHE_FORM_CONTENT );
        if( -1 !== $userId && $object->getUserId() !== $userId ) {
            return false;
        }
        return $object;
    }

    /**
     *  find many FormContent
     *  @param
     *  @return objects or empty array
     */
    public function findFormContents( $userId=-1, $formPageId=-1, $customSearch='', $page=1, $itemsPerPage=APP_ITEMS_PER_PAGE )
    {
        $opt = array(
            'userId'        => $userId,
            'formPageId'    => $formPageId,
            'customSearch'  => $customSearch,
            '_order'        => 'id DESC',
            '_page'         => $page,
            '_itemsPerPage' => $itemsPerPage
        );
        return $this->findMFormContentsReal( $opt );
    }

    /**
     *  get count by "findFormContents" method
     *  @return int
     */
    public function getNumFormContents( $userId=-1, $formPageId=-1, $customSearch='' )
    {
        $opt = array(
            'userId'       => $userId,
            'formPageId'   => $formPageId,
            'customSearch' => $customSearch
        );
        return $this->findMFormContentsReal( $opt, true );
    }

    /**
     *  findMembersReal
     *
     *  "findMembers" and "getNumMembers" SQL query
     *
     *  @return objects or record total
     */
    protected function findMFormContentsReal( $opt=array(), $isGetCount=false )
    {
        $select = $this->getDbSelect();

        if( -1 !== $opt['userId'] ) {
            $select->where->and->equalTo( 'user_id', $opt['userId'] );
        }
        if( -1 !== $opt['formPageId'] ) {
            $select->where->and->equalTo( 'form_page_id', $opt['formPageId'] );
        }

        if( is_array($opt['customSearch']) && count($opt['customSearch'])>0 ) {
            foreach ( $opt['customSearch'] as $key => $value ) {
                if ( !$value ) {
                   continue;
                }
                $select->where->like('custom_search', "%[{$key}]=[{$value}]%" );
            }
        }

        if ( !$isGetCount ) {
            return $this->findObjects( $select, $opt );
        }
        else {
            return $this->numFindObjects( $select );
        }
    }

}

