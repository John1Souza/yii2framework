<?php
/** */

namespace backend\controllers;

use \yii\web\Controller;

/**
 * Class HelloController
 * 
 * @author 
 * @package backend\controllers
 */

 class HelloController extends Controller
 {
     public function actionIndex()
     {
         return $this->render('index');
     }
 }  