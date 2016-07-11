<?php
require 'db_config.php';
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => DBMS . ':host=' . DB_HOST . ';dbname=' . DB_NAME,
            'username' => DB_USER_NAME,
            'password' => DB_PASSWORD,
            'charset' => 'utf8',
            'tablePrefix' => 'rs_',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
//            'useFileTransport' => true,
        ],
    ],
];
