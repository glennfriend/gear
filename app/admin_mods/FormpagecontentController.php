<?php

class FormpageContentController extends ControllerBase
{

    protected $_formPage;

    /**
     *
     */
    protected function beforeExecuteRoute()
    {
        parent::beforeExecuteRoute();
        MenuManager::setMainKey('formpage');

        if( !UserManager::isAdmin() ) {
            $this->redirectMainPage();
            return false;
        }

        $formPageId = (int) InputBrg::get('formPageId');
        if( !$formPageId ) {
            $this->redirect('formpage');
            return false;
        }

        $formPages = new FormPages();
        $formPage = $formPages->getFormPage( $formPageId );
        if( !$formPage ) {
            $this->redirect('formpage');
            return false;
        }

        $this->_formPage = $formPage;
    }

    /**
     *  index
     *  @param array - $customSearch
     *  @param int   - $page
     */
    public function indexAction()
    {
        $page         = (int) InputBrg::get('page');
        $customSearch = InputBrg::query('customSearch');
        $customSearch = FormContentModelHelper::filterCustomSearchField( $customSearch, $this->_formPage );

        $formContents   = new FormContents();
        $myFormContents = $formContents->findFormContents(   -1, $this->_formPage->getId(), $customSearch, $page );
        $rowCount       = $formContents->getNumFormContents( -1, $this->_formPage->getId(), $customSearch );

        //
        $pageLimit = new PageLimit();
        $pageLimit->setBaseUrl( 'formpageContent' );
        $pageLimit->setRowCount( $rowCount );
        $pageLimit->setPage( $page );
        $pageLimit->setParams(array(
            'formPageId'   => $this->_formPage->getId(),
            'customSearch' => $customSearch,
        ));

        $this->view->setVars(array(
            'formPage'      => $this->_formPage,
            'customSearch'  => $customSearch,
            'formContents'  => $myFormContents,
            'pageLimit'     => $pageLimit,
        ));
    }

    /**
     *  edit
     */
    public function editAction()
    {
        $formPageId    = (int) inputBrg::get('formPageId');
        $formContentId = (int) inputBrg::get('formContentId');
        $formContents = new FormContents();
        $formContent = $formContents->getFormContent($formContentId);
        if(!$formContent) {
            $this->redirect('formpageContent');
            return;
        }

        // update only
        if( InputBrg::isPost() ) {

            $fields = $this->_formPage->getProperty('fields');
            $contents = array();
            foreach( $fields as $field ) {
                $key = $field['field'];
                $contents[$key] = 
                    Ydin\Html\Filter::javascriptTags(
                    Ydin\Html\Filter::htmlTags( 
                        InputBrg::get($key)
                    ));
            }

            $formContent->setContents( $contents );
            $formContent->filter();

            if( $fieldsMessages = $formContent->validate() ) {
                FormMessageManager::setFieldMessages( $fieldsMessages );
                FormMessageManager::addErrorResultMessage('更新失敗');
            }
            else {
                $formContents->updateFormContent($formContent);
                FormMessageManager::addSuccessResultMessage('更新成功');
                $this->redirect('formpageContent', array(
                    'formPageId' => $this->_formPage->getId()
                ));
                return;
            }

        }

        // edit and update
        $this->view->setVars(array(
            'formPage'      => $this->_formPage,
            'formContent'   => $formContent,
        ));
    }

    /**
     *  delete
     */
    public function deleteAction()
    {
        $chooseItems = InputBrg::post('chooseItems');
        if( !$chooseItems ) {
            FormMessageManager::addErrorResultMessage('You not choose any item');
            $this->redirect( 'formpageContent', array('formPageId'=>$this->_formPage->getId()) );
            return;
        }

        $successIds = array();
        $failIds = array();
        $formContents = new FormContents();
        foreach( $chooseItems as $itemId ) {
            $itemId = (int) $itemId;
            if( $formContents->deleteFormContent($itemId) ) {
                $successIds[] = $itemId;
            }
            else {
                $failIds[] = $itemId;
            }
        }

        if( $successIds ) {
            FormMessageManager::addSuccessResultMessage('您刪除了('. join(', ',$successIds) .')');
        }
        if( $failIds ) {
            FormMessageManager::addErrorResultMessage('無法刪除('. join(', ',$failIds) .')');
        }

        $this->redirect( 'formpageContent', array('formPageId'=>$this->_formPage->getId()) );
    }

}
