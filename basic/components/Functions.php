<?php
//公用函数方法
namespace app\components;

use yii\helpers\VarDumper;
use yii\web\UploadedFile;

class Functions {
    /**
     * 格式化调试信息
     */
    static public function dumpData ($data, $is_exit = true){
        echo "<pre>";
        VarDumper::dump($data);
        echo "</pre>";
        if ($is_exit) {
            exit ();
        }
    }

    /**
     * JS提示跳转
     */
    static public function redirectJS($msg, $method = 'back', $url = '') {
        header ( "content-Type: text/html; charset=utf-8" );

        $script = '<script type="text/javascript">';
        $script .= 'alert("' . $msg . '");';
        if ($method == 'back') {
            $script .= 'window.history.back();';
        } elseif ($method == 'jump') {
            $script .= 'window.location.href="' . $url . '";';
        }
        $script .= '</script>';

        die ( $script );
    }

    /**
     * 给数组元素添加mysql引号
     * @param $params array
     */
    static public function parseSqlQuot($params){
        if( ! $params ){
            return array();
        }
        foreach ( $params as &$val ){
            $val = '`' . $val . '`';
        }
        return $params;
    }


    /**
     * @param $inputName    上传input的name
     * @param $dirName  存放目录名
     * @param int $isThumbnail  是否缩略图 1:是 0:否
     * @param int $isRandomName 是否随机文件名 1:是 0:否
     * @return array
     */
    static public function uploadPic($inputName, $dirName, $isRandomName = 1, $isThumbnail = 0 ){
        $return = [];

        $uploadFile = UploadedFile::getInstanceByName($inputName);

        if( ! $uploadFile ){
            $return ['status'] = 1;
            $return ['msg'] = '请选择一个文件进行上传';
            return $return;
        }

        $fileExt  = $uploadFile->getExtension();    // 文件后缀
        if( ! in_array($fileExt,[
            'gif',
            'jpg',
            'jpeg',
            'png'
        ]) ){
            $return ['status'] = 1;
            $return ['msg'] = '上传的图片格式必须是jpg、jpeg、gif或png';
            return $return;
        }

        $uploadDir = \Yii::$app->basePath.'/web/'.\Yii::$app->params['uploadFolder'].'/'.$dirName;
        if ($isRandomName) {
            $fileName = md5 ( uniqid () . time () ); // 使用随机名称
        } else {
            $tmp = explode ( '.', $uploadFile->name );
            $fileName = $tmp [0]; // 使用原始名称
        }
        $fileNameExt = $fileName . '.' . $fileExt;
        $uploadPath = $uploadDir . '/' . $fileNameExt;
        if (! $uploadFile->saveAs ( $uploadPath )) {
            $return ['status'] = 1;
            $return ['msg'] = '上传文件失败' . $uploadFile->error;
            return $return;
        }

        $return ['fileName'] = $fileNameExt;
        $return ['status'] = 0;
        $return ['msg'] = '';
        return $return;
    }
}