<?php

namespace app\controllers;

use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\UrlsList;
use app\models\ShortUrlsAlias;

class UrlsController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionRedirect($hash){
        try {
            $url = (new ShortUrlsAlias())->shortHashToUrl($hash);
            $model = new UrlsList();
            $model->link = $url;
            $model->date_year_month = date("Y-m");
            if ( $model->validate()) {
                $model->save();
                header("Location: " . $url);
            } else {
                echo 'validate error';
            }
            exit;
        }
        catch (\Exception $e) {
            header("Location: /error");
            exit;
        }
    }

}
