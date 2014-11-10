<?php

class FormpageWantTahController extends FormControllerBase
{

    protected $_formPageId = 1;

    public function initialize()
    {
        parent::initialize();

        // disabled layout, use action view
        $this->view->setRenderLevel(\Phalcon\Mvc\View::LEVEL_LAYOUT);
    }

    /**
     *  Want Try-At-Home form
     */
    public function formAction()
    {
        $fromSku = (int) InputBrg::get('from_sku');

        $this->view->setVars(array(
            'from_sku' => sprintf("%05d", $fromSku),
        ));
    }

    /**
     *  demo FormPage only
     */
    public function formSubmitAction()
    {
        $formPageId = $this->_formPageId;
        $results = array(
            'result'  => false,
            'error'   => 1,
            'message' => 'empty',
        );

        $formPages = new FormPages();
        $formPage = $formPages->getFormPage( $formPageId );
        if( !$formPage ) {
            $results['error'] = 3;
            $this->_output($results);
        }

        $activeStatus = $formPage->getActiveStatus();
        if( 1 !== $activeStatus ) {
            $results['error'] = 4;
            $results['message'] = (int) $activeStatus;
            $this->_output($results);
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

            // custom $wedding_date from $mm $dd $yyyy
            $saveData['wedding_date'] = 
                sprintf("%02d", InputBrg::post('mm') ) . '/' .
                sprintf("%02d", InputBrg::post('dd') ) . '/' .
                sprintf("%04d", InputBrg::post('yyyy') );
            $saveData['wedding_date'] = Ydin\Html\Filter::htmlTags( $saveData['wedding_date'] );

            // setting
            $formContent->setUserId     ( 0 );
            $formContent->setFormPageId ( $formPageId );
            $formContent->setContents   ( $saveData );
            $formContent->setCreateTime ( time() );
            $formContent->filter();

            if( $fieldsMessages = $formContent->validate() ) {
                // validate fail
                $results['error'] = 5;
                $results['message'] = 'fail';
                $this->_output($results);
            }
            else {
                $formContents = new FormContents();
                $result = $formContents->addFormContent($formContent);
                if( $result ) {
                    // success
                    $results['result'] = true;
                    $results['error'] = 0;
                    $results['message'] = 'Thank you for your interest. We will notify you when this dress becomes available for Try at Home.';
                }
                else {
                    // fail
                    $results['error'] = 2;
                    $results['message'] = 'fail';
                }
            }

        }

        $this->_output($results);
    }

    protected function _output( $results )
    {
        echo json_encode($results);
        exit;
    }

}
