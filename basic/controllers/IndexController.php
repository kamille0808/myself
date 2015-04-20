<?php

namespace app\controllers;

use app\extend\ExtendController;
use Yii;
use yii\web\Controller;
use app\components\Functions;

class IndexController extends ExtendController {

    public $layout = "siteTitle";

    /**
     * 登录页面
     * @return string
     */
    public function actionIndex(){

        return $this->render('login');
    }


    public function  actionLogin(){
        $account = parent::getPost('account');
        $password = parent::getPost('password');

        
    }


}
