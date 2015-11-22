<?php
session_start();
define('MYSQL_HOST', "localhost");
define('MYSQL_USERNAME', "root");
define('MYSQL_PASSWORD', "smemoranda2012");
define('MYSQL_DATABASE', "sitoband");

define('HEADER', $_SERVER['DOCUMENT_ROOT']."/template_pagine/header.php");
define('FOOTER', $_SERVER['DOCUMENT_ROOT']."/template_pagine/footer.php");

define('NEWSMANAGER', "/core/newsManager/newsManager_class.php");
define('DBMANAGER', "/core/dbManager/db_class.php");


include(NEWSMANAGER);
$newsMGR = new news_class();
include(DBMANAGER);
$dbMGR = new db_class();

?>