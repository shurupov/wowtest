<?php

namespace app\controllers;

use Imagick;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    /**
     * @inheritdoc
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
     * @inheritdoc
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
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Метод загрузки.
     *
     * @return string
     */
    public function actionUpload()
    {

        try {

            if (Yii::$app->request->isPost) {

                $id = uniqid();

                $pdf_filename = Yii::getAlias('@webroot') . '/uploads/' . $id;

                move_uploaded_file($_FILES['files']['tmp_name'][0], $pdf_filename);


                /* Это неплохо бы переместить в сервис для pdf */
                $f = fopen($pdf_filename, "r");

                $pageCount = 0;

                while(!feof($f)) {
                    $line = fgets($f,255);
                    if (preg_match('/\/Count [0-9]+/', $line, $matches)){
                        preg_match('/[0-9]+/',$matches[0], $matches2);
                        if ($pageCount < $matches2[0]) $pageCount=$matches2[0];
                    }
                }
                fclose($f);

                Yii::$app->session->set($id, [
                    'id' => $id,
                    'pages' => $pageCount,
                    'page' => 0
                ]);

                return json_encode([
                    'status' => 'ok',
                    'id' => $id,
                    'pages' => $pageCount,
                    'name' => $_FILES['files']['name'][0]
                ]);
            }

        } catch (\Exception $e) {

            return json_encode([
                'status' => 'error'
            ]);

        }

    }

    /**
     * Метод обработки.
     *
     * @return string
     */
    public function actionProcess()
    {

        $id = Yii::$app->request->post('id');

        $data = Yii::$app->session->get($id);

        if ($data['page'] < $data['pages']) {
            $myurl = Yii::getAlias('@webroot') . '/uploads/' . $id . '[' . $data['page'] . ']';
            $image = new Imagick($myurl);
            $image->setResolution( 300, 300 );
            $image->setImageFormat( "png" );
            $image->writeImage( Yii::getAlias('@webroot') . '/pdf-images/' . $id . '_' . $data['page'] . '.png' );

            $data['page'] += 1;

            Yii::$app->session->set(Yii::$app->request->post('id'), $data);
        }

        return json_encode([
            'status' => 'ok',
            'page' => $data['page'],
            'pageCount' => $data['pages']
        ]);

    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
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
