<?php
// ini_set("display_errors", 1);
$dir = dirname(__FILE__);
$webroot = $dir;
$zenddir = $webroot.'/';
$servicepath = $webroot.'/services';
set_include_path(get_include_path().PATH_SEPARATOR.$zenddir);
require_once 'Zend/Loader/Autoloader.php';
Zend_Loader_Autoloader::getInstance();
$server = new Zend_Amf_Server();
$server->setProduction(false);
$server->addDirectory($servicepath);
echo $server->handle();
