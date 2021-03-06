<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginoneForm;
use app\models\LogintwoForm;
use app\models\SignuponeForm;
use app\models\SignuptwoForm;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;

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
                'only' => ['logout', 'demo-one', 'demo-two'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['demo-one'],
                        'allow' => true,
                        'roles' => ['classone'],
                    ],
                    [
                        'actions' => ['demo-two'],
                        'allow' => true,
                        'roles' => ['classtwo'],
                    ],
                ],
                'denyCallback' => function ($rule, $action) {
                    return $this->redirect('/rbac/web/index.php?r=site%2Flogin-one');
                }
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

    public function actionDemoOne(){
        return 1;
    }
    public function actionDemoTwo(){
        return 2;
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * LoginOne action.
     *
     * @return Response|string
     */
    public function actionLoginOne()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginoneForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('loginone', [
            'model' => $model,
        ]);
    }

    /**
     * LogoutOne action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * LoginTwo action.
     *
     * @return Response|string
     */
    public function actionLoginTwo()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LogintwoForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('logintwo', [
            'model' => $model,
        ]);
    }

    public function actionSignupOne() {
        $model = new SignuponeForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }
        return $this->render('signupone', [
            'model' => $model,
        ]);
    }

    public function actionSignupTwo() {
        $model = new SignuptwoForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }
        return $this->render('signuptwo', [
            'model' => $model,
        ]);
    }
    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
