<?php
//不知道为什么设置有问题，没有测试成功，只能做acceptance test，functional test一直做不了，不知道哪里出现了问题！
//require  __DIR__ .'/../../vendor/autoload.php';
$config = include __DIR__ . "/config.php";
include __DIR__ . "/loader.php";
$di = new \Phalcon\DI\FactoryDefault();
include __DIR__ . "/services.php";
return new \Phalcon\Mvc\Application($di);
?>