<?php 
/*
*Author:白小七
*Date:2017-5-8
*/
session_start();
//定义常量，授权调用includes里面的文件
define('IN_TG', true);
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';
_unsetcookie();
?>