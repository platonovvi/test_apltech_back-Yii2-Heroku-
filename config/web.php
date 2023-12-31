<?php
defined('YII_DEBUG') or define('YII_DEBUG', false);
/*defined('YII_ENV') or define('YII_ENV', 'prod');*/
$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

// Парсим данные из DATABASE_URL
$originalDsn = getenv('DATABASE_URL');
$dsnParts = parse_url($originalDsn);
// Удаление первого слэша перед именем базы данных
if (isset($dsnParts['path'])) {
    $dsnParts['path'] = ltrim($dsnParts['path'], '/');
}
$newDsn = "pgsql:host={$dsnParts['host']};port={$dsnParts['port']};dbname={$dsnParts['path']}";
$username = $dsnParts['user'] ?? '';
$password = $dsnParts['pass'] ?? '';
$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'components' => [
        'security' => [
            'class' => 'yii\base\Security',
        ],
        'request' => [
            'class' => 'yii\web\Request',
            'enableCookieValidation' => false,
            'enableCsrfValidation' => false,
            'cookieValidationKey' => 'aBcDeFgH1234567890IjKlMnOpQrStUvWxYz',
        ],
        'response' => [
            'class' => 'yii\web\Response',
            'format' => 'json',
        ],
        /*'cache' => [
            'class' => 'yii\caching\FileCache',
        ],*/
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'products' => 'products/get-products',
                'product/<id:\d+>' => 'products/get-product',
                'product/create' => 'products/create',
                'product/update/<id:\d+>' => 'products/update',
                'product/brand/<name:[\w-]+>' => 'products/find-brand',
                'user/login' => 'user/login',
                'user/logout' => 'user/logout',
                'user/signup' => 'user/signup',
                'user/auth' => 'user/auth',
                '' => 'site/index',
            ],
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => $newDsn,
            'username' => $username,
            'password' => $password,
            'charset' => 'utf8',
            'enableSchemaCache' => true,
            'on afterOpen' => function ($event) {
                Yii::info('Connected to the database!');
            },
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                // ...
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning', 'info'],
                ],
            ],
        ],
        'assetManager' => [
            'bundles' => [
                'yii\web\JqueryAsset' => false, // Отключаем стандартный JqueryAsset
                'yii\bootstrap5\BootstrapAsset' => false, // Отключаем стандартный BootstrapAsset
            ],
        ],
    ],
    'aliases' => [
        '@bower/bootstrap' => '@vendor/bower-asset/bootstrap',
    ],
    'params' => $params,
];
return $config;