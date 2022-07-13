<?php

namespace app\controllers;

use Exception;
use Psr\Http\Message\ResponseInterface;
use React\Http\Browser;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Json;
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

    public function actionConnect($hash)
    {
        $url = "https://api.apilayer.com/user_agent/generate?windows=windows&tablet=tablet&mobile=mobile&mac=mac&linux=linux&ie=ie&firefox=firefox&desktop=desktop&chrome=chrome&android=android";

        $headers = [
            'apikey'=>'KeIgQ5FOQ3gCds8nnp34io6AtR8ZvCxN',
        ];

        // асинхронный запуск
        $browser = new Browser();
        $browser->get($url, $headers)->then(
            function (ResponseInterface $response) {
                // после получения ответа от сервиса проверки UserAgent
                // проверям кто переходит по ссылке
                $result = $response->getBody();
                $resultText = Json::decode($result->getContents(), true);
                // если переходит не бот
                if ( false === $resultText['type']['bot']) {
                    // Проверка признала переход не от бота  $isBot = false;
                    // делаем переадресацию на страницу
                    // с записью данных в таблицу
                    Yii::$app->controllerNamespace = "app\controllers";
                    Yii::$app->runAction("urls/redirect", ['hash' => 'hash']);
                }
            },
            function (Exception $e) {
                echo 'Error: ' . $e->getMessage() . PHP_EOL;
            }
        );
    }

    /**
     * @param $hash
     */
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
