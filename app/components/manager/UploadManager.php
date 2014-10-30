<?php

/*

[file1] => array
(
    [name] => test.csv
    [type] => application/csv-tab-delimited-table
    [tmp_name] => /tmp/phpy9xqjL
    [error] => 0
    [size] => 2948
)

$upload = new UploadManager();
$upload->setFile( $_FILES['file1'] );
$upload->getName();
$upload->getType();
$upload->getTmpName();
$upload->getError();
$upload->getSize();

$upload->setMaxSize( 10 , 'mb' );       // default is -1, default is byte, kb, mb
$upload->addAllowTypeByGroup('csv');
$upload->addAllowType('application/csv-tab-delimited-table');

$path = '/tmp';
$file = 'test_' . date('YmdHis') . '_' . uniqid();
$pathFile = $path . DIRECTORY_SEPARATOR . $file;

// 目錄不存在 會自動建立
$result = $upload->move( $path, $file );        // 目標檔案存在->失敗
$result = $upload->move( $path, $file, true );  // 目標檔案存在->覆蓋
if ( !$result ) {
    echo '<pre style="background-color:#def;color:#000;text-align:left;font-size:10px;font-family:dina,GulimChe;">';
    print_r( $this->getErrorInfo() );
    echo "</pre>\n";
}



*/

class UploadManager
{

    /**
     *  file content
     *  @param array
     */
    protected $file;
    
    /**
     *  size limit
     *  @param int
     */
    protected $maxSize = 0;

    /**
     *  allow type list
     *  @param array
     */
    protected $allowTypes = array();

    /**
     *  error information
     *  @param array
     */
    protected $errorInfo = array();

    /**
     *  set file
     */
    public function setFile( $file )
    {
        $this->file = array();

        if (    is_array($file)
             && isset( $file['name']     )
             && isset( $file['type']     )
             && isset( $file['tmp_name'] )
             && isset( $file['error']    )
             && isset( $file['size']     )     
             && is_uploaded_file( $file['tmp_name'] )
        ) {
            $this->file = $file;
        }

    }

    public function getName()
    {
        return $this->file['name'];
    }
    public function getType()
    {
        return $this->file['type'];
    }
    public function getTmpName()
    {
        return $this->file['tmp_name'];
    }
    public function getError()
    {
        return $this->file['error'];
    }
    public function getSize()
    {
        return $this->file['size'];
    }

    /**
     *  get error information
     */
    public function getErrorInfo()
    {
        return $this->errorInfo;
    }

    /**
     *  set size limit to validate
     */
    public function setMaxSize( $size, $unit='byte' )
    {
        switch( $unit )
        {
            case 'byte':
                $this->maxSize = intval($size);
                break;
            case 'kb':
                $this->maxSize = intval($size) * 1024;
                break;
            case 'mb':
                $this->maxSize = intval($size) * 1024 * 1024;
                break;
        }
    }

    /**
     *  使用 class 自帶的 file type group 快速加入予許的上傳格式
     */
    public function getGroupList()
    {
        return array(
            'csv' => array(
                'text/csv',
                'text/comma-separated-values',
                'application/csv-tab-delimited-table',
                'application/vnd.ms-excel',
                'application/octet-stream',
            ),
            'gif' => array(
                'image/gif',
            ),
            'jpg' => array(
                'image/jpeg',
                'image/pjpeg',
            ),
            'png' => array(
                'image/png',
            ),
            'tif' => array(
                'image/tiff',
                'image/x-tiff',
            ),
            'bmp' => array(
                'image/bmp',
                'image/x-windows-bmp',
            ),
            'ico' => array(
                'image/vnd.microsoft.icon',
            ),
            'psd' => array(
                'image/vnd.adobe.photoshop',
            ),
        );
    }

    /**
     *  加入 予許的上傳格式
     */
    public function addAllowType( $type )
    {
        $this->allowTypes[$type] = true;
    }

    /**
     *  使用 class 自帶的 file type group 快速加入 予許的上傳格式
     */
    public function addAllowTypeByGroup( $group )
    {
        $group = trim(strip_tags($group));
        $list = $this->getGroupList();
        if ( !isset($list[$group]) ) {
            return;
        }

        foreach( $list[$group] as $type ) {
            $this->allowTypes[$type] = true;
        }
    }

    /**
     *  搬移檔案
     *  如果 目錄不存在, 會自動建立
     *
     *  @param path
     *  @param filename
     *  @param force move , 如果目標檔案已存在, false=不搬移 回傳錯誤訊息, true=強迫搬移覆蓋
     *  @return boolean
     */
    public function move( $path, $filename,  $isForceMove=false )
    {
        // filter filename
        $filename = $this->normalizeFilename( $filename );

        // validate $_FILES file
        if ( !$this->file ) {
            $this->errorInfo = array(
                'message' => 'upload file error or empty',
            );
            return false;
        }

        // validate type
        if ( !$this->validateType() ) {
            $this->errorInfo = array(
                'message' => 'file type is not allow type, your type is "'. trim(strip_tags($this->getType())) . '"',
            );
            return false;
        }

        // validate size
        if ( 0 !== $this->maxSize && $this->getSize() > $this->maxSize ) {
            $this->errorInfo = array(
                'message' => 'file size limit exceeded',
            );
            return false;
        }

        // create directory
        if ( !is_dir($path) ) {
            $result = mkdir( $path, 0700, true );
            if( !$result ) {
                $this->errorInfo = array(
                    'message' => 'can not create directory',
                );
                return false;
            }
        }

        //
        $pathFile = $path . DIRECTORY_SEPARATOR . $filename;
        if ( false === $isForceMove && file_exists($pathFile) ) {
            $this->errorInfo = array(
                'message' => 'file is exist',
            );
            return false;
        }

        // move file
        $tmp = $this->getTmpName();
        $result = move_uploaded_file( $tmp, $pathFile );
        if ( !$result ) {
            $this->errorInfo = array(
                'message' => 'can not move uploaded file',
            );
            return false;
        }

        return true;
    }
    
    /* --------------------------------------------------------------------------------
        private function
    -------------------------------------------------------------------------------- */

    /**
     *  filter filename
     *
     *  @see https://github.com/fuelphp/upload/blob/master/src/Fuel/Upload/File.php#L536
     *  @param string, filename
     *  @return string, filename
     */
    protected function normalizeFilename( $filename )
    {
        $normalize_separator = '_';

        // Decode all entities to their simpler forms
        $filename = html_entity_decode($filename, ENT_QUOTES, 'UTF-8');
        
        // Remove all quotes
        $filename = preg_replace("#[\"\']#", '', $filename);
        
        // Strip unwanted characters
        $filename = preg_replace("#[^a-z0-9]#i", $normalize_separator, $filename);
        $filename = preg_replace("#[/_|+ -]+#u", $normalize_separator, $filename);
        $filename = trim($filename, $normalize_separator);
        return $filename;
    }

    /**
     *  validate type
     *
     *  @return boolean
     */
    protected function validateType()
    {
        foreach ( $this->allowTypes as $type => $boolean ) {
            if ( true !== $boolean ) {
                continue;
            }
            if ( $type === $this->getType() ) {
                return true;
            }
        }
        return false;
    }

}
