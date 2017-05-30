<?php 
/*
*Author:白小七
*Date:2017-5-8
*/
//防止恶意调用
if (!defined('IN_TG')) {
	exit('ILLEGAL ACCESS!');
}
//转换成绝对路径常量
define('ROOT_PATH', substr(dirname(__FILE__),0,-8));
//转义常量
define('GPC',get_magic_quotes_gpc());
//拒绝PHP版本
if (PHP_VERSION<'4.1.0') {
	exit('您的php版本太低，请升至4.1版本及以上！');
}
//引入函数库
require ROOT_PATH.'includes/global.func.php';
require ROOT_PATH.'includes/mysql.func.php';
//执行耗时
$_start_time=_runtime();
//数据库连接
define('DB_USER','root');
define('DB_PWD','118710');
define('DB_HOST','localhost');
define('DB_NAME','multisystem');
//初始化数据库
_connect();
_select_db();
_set_names();
//短信提醒
$_message=_fetch_array("SELECT COUNT(tg_id) AS count FROM tg_message WHERE tg_state=0 AND tg_fromuser='{$_COOKIE['username']}'");
if (empty($_message['count'])) {
	$_message_html='<strong class="read"><a href="member_message_outbox.php">(0)</a></strong>';
}else{
	$_message_html='<strong class="noread"><a href="member_message_outbox.php">('.$_message['count'].')</a></strong>';
}
//鲜花提醒
$_result=_query("SELECT tg_flower FROM tg_flower WHERE tg_touser='{$_COOKIE['username']}'");
while(!!$_rows=_fetch_array_list($_result,MYSQL_ASSOC)) {
	$_flower=$_rows['tg_flower'];
	$_flower_count+=$_flower;
}
if (!empty($_flower_count)) {
	$_flower_html='<strong class="flower"><a href="member_flower.php">('.$_flower_count.')</a></strong>';
}else{
	$_flower_html='<strong class="noflower"><a href="member_flower.php">(0)</a></strong>';
}
//网站系统初始化
if (!!$_rows=_fetch_array("SELECT
	tg_webname,tg_artical,tg_blog,tg_photo,tg_skin,tg_string,tg_post,tg_re,tg_code,tg_register FROM tg_system WHERE tg_id=1 LIMIT 1")) {
	$_system=array();
	$_system['webname']=$_rows['tg_webname'];
	$_system['artical']=$_rows['tg_artical'];
	$_system['blog']=$_rows['tg_blog'];
	$_system['photo']=$_rows['tg_photo'];
	$_system['skin']=$_rows['tg_skin'];
	$_system['string']=$_rows['tg_string'];
	$_system['post']=$_rows['tg_post'];
	$_system['re']=$_rows['tg_re'];
	$_system['code']=$_rows['tg_code'];
	$_system['register']=$_rows['tg_register'];
	$_system=_html($_system);
	//判断COOKIE是否存在
	if ($_COOKIE['skin']) {
		$_system['skin']=$_COOKIE['skin'];
	}
}else{
	exit('The system table is abnormal. Please contact the administrator!');
}
?>