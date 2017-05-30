<?php 
/*
*Author:白小七
*Date:2017-5-8
*/
//定义常量，授权调用includes里面的文件
define('IN_TG', true);
//定义常量，用来指定本页的内容
define('SCRIPT','skin');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';
$_skinurl=$_SERVER["HTTP_REFERER"];
if (empty($_skinurl)||!isset($_GET['id'])) {
	_alert_back('ILLEGAL ACCESS!');
}else{
	if ($_GET['id']==1||$_GET['id']==2||$_GET['id']==3) {
		setcookie('skin',$_GET['id']);
		_location(null,$_skinurl);
	}
}
?>