<?php
/**
 * Created by PhpStorm.
 * User: shurupov
 * Date: 22.10.17
 * Time: 18:09
 */

namespace app\assets;

use yii\web\AssetBundle;

class JqueryUpload extends AssetBundle
{
    public $sourcePath = '@webroot/jquery-upload';
//    public $baseUrl = '@web';
    public $css = [
        'css/style.css',
        'css/jquery.fileupload.css'
    ];
    public $js = [
        'js/cors/jquery.postmessage-transport.js',
        'js/cors/jquery.xdr-transport.js',
        'js/vendor/jquery.ui.widget.js',
        'js/jquery.fileupload.js',
        'js/jquery.iframe-transport.js',
//        'js/app.js',
//        'js/main.js',
    ];
    public $depends = [
        'yii\bootstrap\BootstrapAsset',
    ];
}