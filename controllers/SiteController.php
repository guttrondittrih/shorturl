<?php

namespace app\controllers;

use Yii;
use yii\base\BaseObject;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

use app\models\UrlsList;

use app\models\ShortUrlsAlias;

class SiteController extends Controller
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

    /**
     * @return string|Response
     */
    public function actionUrls()
    {
        $model = new ShortUrlsAlias();

        return $this->render('urls', [
            'model' => $model,
        ]);
    }

    /**
     * @return string
     */
    public function actionPrepared()
    {
        $request = Yii::$app->request;
        
        if ($request->isPost && $request->isAjax)
        {
            $formModel = new ShortUrlsAlias();
            if($formModel->load(\Yii::$app->request->post())){

                $formLink = ($formModel->getLink());

                $model = ShortUrlsAlias::createShort($formLink);

                return $model->getShort();
            }
        } else {
            return 'request is not Ajax or is not POST';
        }
    }
}
