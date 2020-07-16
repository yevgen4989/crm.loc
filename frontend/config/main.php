<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language' => 'ru-RU',
    'name' => 'CRM',
    'homeUrl' => '/',
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'formatter' => [
            'dateFormat' => 'dd.MM.yyyy',
            'datetimeFormat' => 'dd.MM.yyyy H:i:s',
            'timeFormat' => 'php:H:i:s',
            'decimalSeparator' => ',',
            'thousandSeparator' => '.',
            'currencyCode' => 'RUB',
            'numberFormatterSymbols' => [
                NumberFormatter::CURRENCY_SYMBOL => '₽',
            ],
            'numberFormatterOptions' => [
                NumberFormatter::MIN_FRACTION_DIGITS => 1,
                NumberFormatter::MAX_FRACTION_DIGITS => 3,
            ],
            'defaultTimeZone' => 'UTC',
            'timeZone' => 'Europe/Moscow',
            'locale' => 'ru-RU'
        ],

        'request' => [
            'csrfParam' => '_csrf-frontend',
            'baseUrl' => ''
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
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
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '' => 'site/index',
                '<controller:\w+>/<action:\w+>/' => '<controller>/<action>',
            ],
        ],
    ],
    'params' => $params,
    'modules' => [
        'dynagrid'=> [
            'class'=>'\kartik\dynagrid\Module',
            'maxPageSize' => 1000,
        ],
        'gridview'=> [
            'class'=>'\kartik\grid\Module',
            // other module settings
        ],
    ]
];
