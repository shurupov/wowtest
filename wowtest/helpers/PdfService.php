<?php

namespace app\helpers;
use app\models\Document;
use Imagick;
use Yii;

/**
 * Created by PhpStorm.
 * User: shurupov
 * Date: 29.10.17
 * Time: 20:16
 */
class PdfService
{
    private function getPdfPath($id)
    {
        return Yii::getAlias('@webroot') . '/uploads/' . $id . '.pdf';
    }

    public function startProcess()
    {
        if (Yii::$app->request->isPost) {

            $id = uniqid();

            $pdf_filename = $this->getPdfPath($id);

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

        return json_encode([
            'status' => 'error'
        ]);

    }

    public function process()
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
}