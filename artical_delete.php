<?php
/*
*Author:白小七
*Date:2017-5-8
*/
session_start();
//定义常量，授权调用includes里面的文件
define('IN_TG',true);
//定义常量，用来指定本页的内容
define('SCRIPT','artical');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';
//是否管理员
_manage_login();
//删除主题帖
if ($_GET['action']=='delete'&&isset($_GET['id'])) {
	if (!!$_rows=_fetch_array("SELECT tg_uniqid FROM tg_user WHERE tg_username='{$_COOKIE['username']}' LIMIT 1")) {
		//防止cookie伪造,比对数据库唯一标识符与cookie中存的一致
		_uniqid($_rows['tg_uniqid'],$_COOKIE['uniqid']);
		//删除会员
		_query("DELETE FROM tg_artical WHERE tg_id='{$_GET['id']}' OR tg_reid='{$_GET['id']}'");
	}
	if (_affected_rows()) {
		_close();
		_location('Delete SUCCESS!','index.php');
	}else{
		_close();
		_alert_back('Delete FAIL!');
	}
}else{
	_alert_back('ILLEGAL operation!');
}
?>