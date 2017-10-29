<?php

namespace app\helpers;

use PHPUnit_Framework_TestCase;
use Yii;

/**
 * Created by PhpStorm.
 * User: shurupov
 * Date: 29.10.17
 * Time: 21:34
 */
class PdfServiceTest extends PHPUnit_Framework_TestCase
{
    public function testGetPdfPath()
    {
        $test = "12345";
        $expected = Yii::getAlias('@webroot') . '/uploads/12345.pdf';

        $pdfService = new PdfService();

        $result = $pdfService->getPdfPath($test);

        $this->assertEquals($expected, $result);
    }
}