<?php

$params = require(__DIR__ . '/params.php');
$db = require(__DIR__ . '/db.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'XkurcRK6ZSealQObUebVD7cOD1WZBmbp',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'result/<id>' => 'site/result',
                'upload' => 'site/upload',
                'result/<id>/<page>' => 'site/result',
                'download/<id>' => 'site/download',
                'image-list' => 'site/images'
            ],
        ],

        'view' => [
            'class' => 'yii\web\View',
            'renderers' => [
                'twig' => [
                    'class' => 'yii\twig\ViewRenderer',
                    'cachePath' => '@runtime/Twig/cache',
                    // Array of twig options:
                    'options' => [
                        'auto_reload' => true,
                        'debug' => true,
                    ],
                    'globals' => [
                        'html'   => ['class' => '\yii\helpers\Html'],
                        'arhelp' => ['class' => '\yii\helpers\ArrayHelper'],
                        'url'    => ['class' => '\yii\helpers\Url']
                    ],
                    'uses' => ['yii\bootstrap'],
                    'extensions' => ['Twig_Extension_Debug']
                ],

            ],
            'defaultExtension' => /*'php'*/'html.twig'
        ],

        'pdf' => array(
            'class' => 'app\helpers\PdfService'
        ),

        'zip' => array(
            'class' => 'app\helpers\ZipService'
        ),

    ],
    'params' => $params,
];

/*if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['192.168.*.*'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['192.168.*.*'],
    ];
}*/

return $config;
