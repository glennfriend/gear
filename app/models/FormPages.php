<?php

/**
 *
 */
class FormPages extends ZendModel
{
    const CACHE_FORM_PAGE = 'cache_form_page';

    /**
     *  table name
     */
    protected $tableName = 'form_pages';

    /**
     *  get method
     */
    protected $getMethod = 'getFormPage';

    /**
     *  get db object by record
     *  @param  row
     *  @return TahScan object
     */
    public function mapRow( $row )
    {
        $object = new FormPage();
        $object->setId          ( $row['id']                      );
        $object->setName        ( $row['name']                    );
        $object->setKeyword     ( $row['keyword']                 );
        $object->setStatus      ( $row['status']                  );
        $object->setTimeStart   ( strtotime($row['time_start'])   );
        $object->setTimeEnd     ( strtotime($row['time_end'])     );
        $object->setCreateTime  ( strtotime($row['create_time'])  );
        $object->setProperties  ( unserialize($row['properties']) );
        return $object;
    }

    /* ================================================================================
        write database
    ================================================================================ */

    /**
     *  update FormPage
     *  @param FormPage object
     *  @return int
     */
    public function updateFormPage( $object )
    {
        $result = $this->updateObject( $object );
        if( !$result ) {
            return false;
        }
        $this->preChangeHook( $object );
        return $result;
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
            $cacheKey = $this->getFullCacheKey( $object->getId(), FormPages::CACHE_FORM_PAGE );
            CacheBrg::remove( $cacheKey );
        }
    }


    /* ================================================================================
        access database
    ================================================================================ */

    /**
     *  get FormPage by id
     *  @param  int id
     *  @return object or empty array
     */
    public function getFormPage( $id )
    {
        return $this->getObject( 'id', $id, FormPages::CACHE_FORM_PAGE );
    }

    /**
     *  find many FormPage
     *  @param
     *  @return objects or empty array
     */
    public function findFormPages( $status=FormPage::STATUS_ALL, $searchKey='', $page=1, $itemsPerPage=APP_ITEMS_PER_PAGE )
    {
        $opt = array(
            'status'         => $status,
            '_searchKey'     => $searchKey,
            '_order'         => '',
            '_page'          => $page,
            '_itemsPerPage'  => $itemsPerPage,
        );
        return $this->findMFormPagesReal( $opt );
    }

    /**
     *  get count by "findFormPages" method
     *  @return int
     */
    public function getNumFormPages( $status=FormPage::STATUS_ALL, $searchKey='' )
    {
        $opt = array(
            'status'     => $status,
            '_searchKey' => $searchKey,
        );
        return $this->findMFormPagesReal( $opt, true );
    }

    /**
     *  findMembersReal
     *
     *  "findMembers" and "getNumMembers" SQL query
     *
     *  @return objects or record total
     */
    protected function findMFormPagesReal( $opt=array(), $isGetCount=false )
    {
        $select = $this->getDbSelect();

        if( FormPage::STATUS_ALL !== $opt['status'] ) {
            $select->where( 'status', $opt['status'] );
        }
        if( '' !== $opt['_searchKey'] ) {
            $value = '%'. $opt['_searchKey'] .'%';
            $select->where->nest
                ->or->like('name',    $value )
                ->or->like('keyword', $value )
            ;
        }

        if ( !$isGetCount ) {
            return $this->findObjects( $select, $opt );
        }
        else {
            return $this->numFindObjects( $select );
        }
    }

}

