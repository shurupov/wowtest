<?php

namespace app\controllers;

use app\models\Document;
use Imagick;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use ZipArchive;

class SiteController extends Controller
{

    public $layout = false;

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
        $documents = Document::find()->
            where('created > ' . (time() - 60 * 30))->
            orderBy('created DESC')->
            all();

        return $this->render('index', [
            'documents' => $documents
        ]);
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
                $document->created = 0;

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
            $image->writeImage( Yii::getAlias('@webroot') . '/images/' . $id . '_' . $data['page'] . '.png' );

            $data['page'] += 1;

            Yii::$app->session->set(Yii::$app->request->post('id'), $data);

            if ($data['page'] == ($data['pages'] - 1)) {
                $document = Document::findOne($id);
                $document->created = time();
                $document->save(false);
            }
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
            throw new NotFoundHttpException('Документ не найден');
        }

        return $this->render('slider', [
            'id' => $id,
            'document' => $document,
            'page' => $page
        ]);
    }

    public function actionDownload($id)
    {
        $document = Document::findOne($id);

        if (!$document || (time() - $document->created > 30 * 60)) {
            throw new NotFoundHttpException('Документ не найден');
        }

        $zipPath = Yii::getAlias('@webroot') . '/archives/' . $id . '.zip';

        if (file_exists($zipPath)) {
            return $this->redirect('/archives/' . $id . '.zip');
        }

        $zip = new ZipArchive();

        if ($zip->open($zipPath, ZipArchive::CREATE)!==TRUE) {
            throw new ServerErrorHttpException('Произошла непредвиденная ошибка');
        }

        $zip->addEmptyDir('assets');

        $zip->addFile( Yii::getAlias('@webroot') . '/css/bootstrap.css', 'assets/bootstrap.css' );
        $zip->addFile( Yii::getAlias('@webroot') . '/css/site.css',      'assets/site.css' );

        $zip->addEmptyDir('images');
        for ( $i = 0; $i < $document->pages_count; $i++ ) {
            $zip->addFile(
                Yii::getAlias('@webroot') . '/images/' . $id . '_' . $i . '.png',
                'images/' . $id . '_' . $i . '.png'
            );

            $html = $this->render('slider', [
                'id' => $id,
                'document' => $document,
                'page' => $i,
                'static' => true
            ]);

            $zip->addFromString($i . '.html', $html);
        }

        $zip->close();

        return $this->redirect('/archives/' . $id . '.zip');
    }

}
