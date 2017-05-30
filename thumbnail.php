<?php 
/*
*Author:白小七
*Date:2017-5-8
*/
session_start();
//定义常量，授权调用includes里面的文件
define('IN_TG', true);
//定义常量，用来指定本页的内容
define('SCRIPT','thumbnail');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';
//生成缩略图
if (isset($_GET['filename'])&&isset($_GET['percent'])) {
	_thumbnail($_GET['filename'],$_GET['percent']);
}
?>