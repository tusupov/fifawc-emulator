<?php

//Настройки
define("DB_HOST",     "localhost");
define("DB_NAME",     "fifawc");
define("DB_USERNAME", "root");
define("DB_PASSWORD", "");
define("DB_CHARSET",  "utf8");
define("DB_PORT",     3306);

//автозагрузка классов
spl_autoload_register(function($className) {
    require_once dirname(__FILE__)."/".$className.'.php';
});


//Подключение к БД
global $db;
$db = new FifaWC\DB(array(
    "host"     => DB_HOST,
    "username" => DB_USERNAME,
    "password" => DB_PASSWORD,
    "db"       => DB_NAME,
    "port"     => DB_PORT,
    "charset"  => DB_CHARSET,
));

