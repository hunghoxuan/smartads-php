<?php 
 return 
[
  'vendorPath' => dirname(__DIR__) . "/vendor",
  'components' => 
  [
    'redis' => 
    [
      'class' => 'yii\\redis\\Connection',
      'hostname' => 'localhost',
      'port' => 6379,
      'database' => 0,
    ],
    'cache' => 
    [
      'class' => 'yii\\caching\\FileCache',
    ],

    'session' =>
    [
      'class' =>'yii\web\Session', //'yii\web\Session', //'yii\redis\Session',
    ],


      'db' =>
          [
              'class' => 'yii\\db\\Connection',
              'dsn' => 'mysql:host=localhost;dbname=stech_smartads',
              'username' => 'root',
              'password' => '',
              'charset' => 'utf8',
              'enableSchemaCache' => true,
              'schemaCacheDuration' => 3600,
              'schemaCache' => 'session',
          ],
    'mailer' => 
    [
      'class' => 'yii\\swiftmailer\\Mailer',
      'viewPath' => '@common/mail',
      'useFileTransport' => false,
      'transport' => 
      [
        'class' => 'Swift_SmtpTransport',
        'host' => 'smtp.gmail.com',
        'username' => 'support@moza-tech.com',
        'password' => 'Mozam0ney007',
        'port' => '587',
        'encryption' => 'tls',
      ],
    ],
    'formatter' => 
    [
      'class' => 'yii\\i18n\\Formatter',
      'nullDisplay' => '',
    ],
    'i18n' => 
    [
      'translations' => 
      [
        'common*' => 
        [
          'class' => 'common\\components\\FMessageSource',
          'basePath' => '@common/messages',
          'fileMap' => 
          [
            'common' => 'common.php',
          ],
        ],
      ],
    ],
    'mail' => 
    [
      'class' => 'yii\\swiftmailer\\Mailer',
      'useTransport' => '',
      'transport' => 
      [
        'class' => 'Swift_SmtpTransport',
        'host' => 'smtp.gmail.com',
        'username' => 'support@moza-tech.com',
        'password' => 'Mozam0ney007',
        'port' => '587',
        'encryption' => 'tls',
      ],
    ],
    '' => 
    [
      'class' => 'yii\\db\\Connection',
      'dsn' => 'mysql:host=localhost;dbname=moza_website1',
      'username' => 'root',
      'password' => '',
      'charset' => 'utf8',
      'enableSchemaCache' => true,
      'schemaCacheDuration' => 3600,
      'schemaCache' => 'session',
    ],
  ],
]; ?>