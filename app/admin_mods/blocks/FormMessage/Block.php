<?php

namespace Blocks\FormMessage;

/**
 *  show form message
 *      - 使用 bootstrap theme
 *      - 會清除已堆積的訊息
 *
 */
class Block extends \Blocks\BaseBlock
{

    protected $data = array();

    public function __construct()
    {
        // 無任何訊息
        $resultMessages = \FormMessageManager::getResultMessages();
        if( !$resultMessages ) {
            return;
        }

        // 清除暫存的訊息
        \FormMessageManager::clearResultMessages();

        // 將訊息組合起來
        $this->data = array();
        $index = 0;
        $previousMessages = '';
        foreach( $resultMessages as $messages ) {

            switch( $messages['type'] ) {
                case 'error':
                    $class = "alert-danger";
                    $alert = "Error";
                    break;
                case 'success':
                    $class = "alert-success";
                    $alert = "Success";
                    break;
                default:
                    // 未設定則輸出 info style
                    $class = "alert-info";
                    $alert = "Info";
            }

            if( $previousMessages && $previousMessages['type']==$messages['type'] ) {
                $this->data[$index]['messages'][] = $messages['message'];
            }
            else {
                $index++;
                $this->data[$index]['type']       = $messages['type'];
                $this->data[$index]['class']      = $class;
                $this->data[$index]['alert']      = $alert;
                $this->data[$index]['messages'][] = $messages['message'];
            }

            $previousMessages = $messages;
        }

    }

    public function tag( $messages, $tag )
    {
        $content = '';
        foreach ( $messages as $message ) {
            $content .= "<{$tag}>{$message}</{$tag}>";
        }
        return $content;
    }

}

