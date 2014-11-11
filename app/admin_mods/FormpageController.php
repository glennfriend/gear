<?php

class FormpageController extends ControllerBase
{

    public function beforeExecuteRoute()
    {
        parent::beforeExecuteRoute();
        MenuManager::setMainKey('formpage');

        if( !UserManager::isAdmin() ) {
            $this->redirectMainPage();
            return false;
        }
    }

    /**
     *  index
     */
    public function indexAction()
    {
        MenuManager::setSubKey('list');

        $page        = (int) inputBrg::get('page',1);
        $searchKey   = trim(strip_tags( inputBrg::get('searchKey') ));

        $formPages   = new FormPages();
        $myFormPages = $formPages->findFormPages(   FormPage::STATUS_ALL, $searchKey, $page );
        $rowCount    = $formPages->getNumFormPages( FormPage::STATUS_ALL, $searchKey );

        $pageLimit = new PageLimit();
        $pageLimit->setBaseUrl( 'form/home/index' );
        $pageLimit->setRowCount( $rowCount );
        $pageLimit->setPage( $page );
        $pageLimit->setParams(Array(
            'searchKey' => $searchKey,
        ));

        $this->view->setVars(Array(
            'searchKey' => $searchKey,
            'formPages' => $myFormPages,
            'pageLimit' => $pageLimit,
        ));
    }

    /**
     *  setting
     */
    public function settingAction()
    {
        if( !UserManager::isAdmin() ) {
            $this->redirectMainPage();
            return;
        }

        $formPageId = InputBrg::get('formPageId');
        $formPages = new FormPages();
        $formPage  = $formPages->getFormPage( $formPageId );
        if( !$formPage ) {
            $this->redirect('form/home');
            return;
        }

        // update setting
        if( InputBrg::isPost() ) {
            $content = InputBrg::post('content');
            $formatContents = $this->_parserContentFormat( $content );
            $formPage->setProperty('fields', $formatContents );
            
            $result = $formPages->updateFormPage( $formPage );
            if( !$result ) {
                FormMessageManager::addSuccessResultMessage('沒有更新任何資料');
            }
            else {
                FormMessageManager::addSuccessResultMessage('更新成功');
            }

            $this->redirect( 'formpage', array('formPageId'=>$formPageId) );
            return;
        }

        $this->view->setVars(array(
            'formPage' => $formPage,
        ));
    }

    /**
     *  解析 特定文字格式 to 資料陣列
     *
     *  example
     *
     *      from :
     *          email     ; 電子郵件
     *          from_info ; 從何處得知網站 ; radio ; 搜尋引擎,其它
     *          
     *      to :
     *          [0] => Array
     *                  [field] => email
     *                  [topic] => 電子郵件
     *                  [type] => 
     *                  [format] => Array()
     *          [1] => Array
     *                  [field] => from_info
     *                  [topic] => 從何處得知網站
     *                  [type] => radio
     *                  [format] => Array(
     *                          [0] => 搜尋引擎
     *                          [1] => 其它
     *                  )
     *      
     */
    protected function _parserContentFormat( $text )
    {
        $items = array();
        $lines = explode("\n", trim($text) );

        foreach( $lines as $line ) {
            $item = explode(';', $line );
            if ( !is_array($item) ) {
                continue;
            }

            $key = trim($item[0]);
            if ( !$key || !preg_match('/^[a-z\_]+$/is', $key) ) {
                continue;
            }

            if ( 1 == count($item) ) {
                $topic = trim(ucwords(preg_replace('/_/', ' ', $key )));
            }
            if ( count($item) >= 2 ) {
                $topic = trim($item[1]);
            }

            $type = '';
            if ( count($item) >= 3 ) {
                $type = strtolower(trim($item[2]));
            }

            $format = array();
            if ( count($item) >= 4 ) {
                foreach( explode(",", $item[3]) as $tmp ) {
                    $tmp = trim($tmp);
                    if ($tmp) {
                        $format[] = $tmp;
                    }
                }
            }

            $items[$key] = array(
                'field'  => $key,
                'topic'  => $topic,
                'type'   => $type,
                'format' => $format,
            );
        }

        return $items;
    }


    /**
     *  demo FormPage only
     */
    public function demoAction()
    {
        $formPageId = (int) InputBrg::get('formPageId');

        $formPages = new FormPages();
        $formPage = $formPages->getFormPage( $formPageId );
        if( !$formPage ) {
            echo 'not found form page id';
            exit;
        }

        $activeStatus = $formPage->getActiveStatus();
        if( 1 !== $activeStatus ) {
            echo "form page active status is [{$activeStatus}]";
            exit;
        }

        // add only
        $formContent = new FormContent();
        if( InputBrg::isPost() ) {

            $fields = $formPage->getProperty('fields');
            $saveData = Array();
            foreach( $fields as $field ) {
                $key = $field['field'];
                $saveData[$key] = 
                    Ydin\Html\Filter::javascriptTags(
                    Ydin\Html\Filter::htmlTags( 
                        InputBrg::post($key)
                    ));
            }

            $formContent->setUserId     ( 0 );
            $formContent->setFormPageId ( $formPageId );
            $formContent->setContents   ( $saveData );
            $formContent->setCreateTime ( time() );
            $formContent->filter();

            if( $fieldsMessages = $formContent->validate() ) {
                // validate fail                
            }
            else {
                $formContents = new FormContents();
                $result = $formContents->addFormContent($formContent);
                if( $result ) {
                    // success
                }
                else {
                    // fail
                }
            }

        }

        $this->view->setVars(array(
            'formPage' => $formPage,
        ));
    }

}
