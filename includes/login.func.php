<?php 
/*
*Author:白小七
*Date:2017-5-8
*/
//防止恶意调用
if (!defined('IN_TG')) {
	exit('ILLEGAL ACCESS!');
}
if (!function_exists('_alert_back')) {
	exit('_alert_back()函数不存在！');
}
//检查用户名
function _check_username($_string){
	//去掉空格
	$_string=trim($_string);
	//判断长度
	if(mb_strlen($_string,'utf-8')<2||mb_strlen($_string,'utf-8')>20){
		_alert_back('用户名长度小于2位或大于20位！');
	}
	//限制特殊字符
	$_char_pattern='/[\/\.?\<>{}\'\"\ \	]/';
	if(preg_match($_char_pattern,$_string)){
		_alert_back('用户名包含非法字符！');
	}
	//特殊字符转义
	$_string=mysql_escape_string($_string);
	return _mysql_string($_string);
}
//检查密码
function _check_passward($_pass){
	//判断密码
	if(strlen($_pass)<6){
		_alert_back('密码不能小于6位！');
	}
	return sha1($_pass);
}
//记住密码
function _check_time($_string){
	$_time=array('0','1','2','3');
	if (!in_array($_string,$_time)) {
		_alert_back('记住密码错误！');
	}
	return _mysql_string($_string);
}
//设置cookie 
function _setcookies($_username,$_uniqid,$_time){
	switch ($_time) {
		case '0'://浏览器进程
			setcookie('username',$_username);
			setcookie('uniqid',$_uniqid);
			break;
		case '1'://一天
			setcookie('username',$_username,time()+86400);
			setcookie('uniqid',$_uniqid,time()+86400);
			break;
		case '2'://一周
			setcookie('username',$_username,time()+604800);
			setcookie('uniqid',$_uniqid,time()+604800);
			break;
		case '3'://一月
			setcookie('username',$_username,time()+2592000);
			setcookie('uniqid',$_uniqid,time()+2592000);
			break;
	}
}
?>