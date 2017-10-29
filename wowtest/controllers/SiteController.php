<?php

namespace app\controllers;

use app\models\Document;
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

            return Yii::$app->pdf->startProcess();

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
        return Yii::$app->pdf->process();
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

    public function actionImages($id)
    {
        $document = Document::findOne($id);

        if (!$document || (time() - $document->created > 30 * 60)) {
            throw new NotFoundHttpException('Документ не найден');
        }

        $result = [];

        for ($i = 0; $i < $document->pages_count; $i++) {
            $result[] = '/images/' . $id . '_' . $i . '.png';
        }

        return $this->asJson($result);
    }

}
