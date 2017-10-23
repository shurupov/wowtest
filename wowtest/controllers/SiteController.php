<?php

namespace app\controllers;

use app\models\Document;
use Imagick;
use Yii;
use yii\web\Controller;

class SiteController extends Controller
{

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
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

                $pdf_filename = Yii::getAlias('@webroot') . '/uploads/' . $id . '.pdf';

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

                $document = new Document();
                $document->id = $id;
                $document->name = $_FILES['files']['name'][0];
                $document->pages_count = $pageCount;
                $document->created = time();

                $document->save(false);

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
            $myurl = Yii::getAlias('@webroot') . '/uploads/' . $id . '.pdf[' . $data['page'] . ']';
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

    public function actionResult($id, $page = 0)
    {
        $document = Document::findOne(['id' => $id]);

        if (!$document || (time() - $document->created > 30 * 60)) {
            return $this->redirect('/');
        }

        return $this->render('slider', [
            'id' => $id,
            'document' => $document,
            'page' => $page
        ]);
    }

    public function actionDownload($id)
    {
        $document = Document::findOne(['id' => $id]);

        if (!$document || (time() - $document->created > 30 * 60)) {
            return $this->redirect('/');
        }

        return "download " . $id;
    }

}
