<?php

namespace app\controllers;

use app\models\ContactForm;
use app\models\LoginForm;
use Yii;
use yii\web\Response;

class SiteController extends Controller
{
    public function actionIndex()
    {
        $content = 'Content';
        //return $this->renderContent($content);
        return $this->render('index'); // Возвращаем представление для действия index
    }
}
