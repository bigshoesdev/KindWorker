<?php

require 'common.php';

$config = require (DB_CONFIG_PATH . '/web.php');

require (VENDOR_PATH . 'autoload.php');
require (VENDOR_PATH . 'yiisoft/yii2/Yii.php');
(new yii\web\Application ( $config ))->run ();
