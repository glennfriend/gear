<?php

    class FormContentModelHelper
    {

        /**
         *  filter incorrect custom field
         *  只過濾 欄位 是否符合 formPage 設定 予許 的值, 並不會過濾任何欄位中的值
         *
         *  @param array  - $customSearch 
         *  @param object - $formPage
         *  @return array
         */
        public static function filterCustomSearchField( $customSearch , $formPage ) 
        {
            if( !$customSearch ) {
                return Array();
            }

            $correctFields = $formPage->getProperty('fields');
            $correctFields = array_keys($correctFields);
            $allowCustomSearch = array();
            foreach( $customSearch as $key => $value ) {
                if( in_array( $key, $correctFields ) ) {
                    $allowCustomSearch[$key] = $value;
                }
            }

            return $allowCustomSearch;
        }

        /**
         *  解析 custom search to SQL
         *  
         *  example
         *  
         *      from array
         *      (
         *          [email] => a
         *          [gender] => 紳士
         *          [from] =>                                   // 存在, 但是沒輸入任何值
         *          [Cross Site Scripting] => drop database     // 不存在的欄位
         *      )
         *      to array
         *      (
         *          [where] =>  ( `custom_search` like :email AND `custom_search` like :gender ) 
         *          [params] => Array
         *              (
         *                  [:email] => %a%
         *                  [:gender] => %紳士%
         *              )
         *      )
         *  
         *  @param string
         *  @return Array( 'where'=>Array() , 'params'=>Array() ) by Yii Active Record format
         *          or empty string
         */
        /*
        public static function parserCustomSearch( $customSearch )
        {
            if( !$customSearch ) {
                return '';
            }

            $where = Array();
            $params = '';
            foreach( $customSearch as $key => $value ) {
                if( !$value ) {
                    continue;
                }
                $where[]          = "`custom_search` LIKE :{$key}";
                $params[':'.$key] = '%'.$value.'%';
            }

            if( $where ) {
                $where = ' ( '. join(' AND ',$where) .' ) ';
            }
            else {
                $where = '';
            }

            return Array(
                'where'  => $where,
                'params' => $params,
            );
        }
        */
    }

