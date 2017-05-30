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
//唯一标识符验证
function _check_uniqid($_first_uniqid,$_second_uniqid){
	if (strlen($_first_uniqid)!=40||($_first_uniqid!=$_second_uniqid)) {
		_alert_back('Unique identifier exception!');
	}
	return _mysql_string($_first_uniqid);
}
//检查用户名
function _check_username($_string){
	//去掉空格
	$_string=trim($_string);
	//判断长度
	if(mb_strlen($_string,'utf-8')<2||mb_strlen($_string,'utf-8')>20){
		_alert_back('Username length is less than 2 bits or greater than 20 bits!');
	}
	//限制特殊字符
	$_char_pattern='/[\/\.?\<>{}\'\"\ \	]/';
	if(preg_match($_char_pattern,$_string)){
		_alert_back('Username contains illegal characters!');
	}
	//特殊字符转义
	$_string=mysql_escape_string($_string);
	return _mysql_string($_string);
}
//检查密码
function _check_passward($_first_pass,$_second_pass){
	//判断密码
	if(strlen($_first_pass)<6){
		_alert_back('密码长度不能小于6位！');
	}
	//判断密码和密码确认一致
	if($_first_pass!=$_second_pass){
		_alert_back('密码不一致！');
	}
	return _mysql_string(sha1($_first_pass));
}
//密码修改验证
function _check_modify_passward($_string){
	//判断密码
	if (!empty($_string)) {
		if(strlen($_string)<6){
			_alert_back('密码长度不能小于6位！');
		}
	}else{
		return null;
	}
	return sha1($_string);
}
//密码提示
function _check_question($_string){
	$_string=trim($_string);
	if(mb_strlen($_string,'utf-8')<2){
		_alert_back('密码提示错误！');
	}
	return _mysql_string($_string);
}
//密码回答
function _check_answer($_ques,$_answ){
	$_answ=trim($_answ);
	if(mb_strlen($_answ,'utf-8')<2){
		_alert_back('密码回答不能小于2位！');
	}
	//密码提示不能为空
	if($_ques==null){
		_alert_back('密码提示不能为空！');
	}
	//密码提示与回答不能一样
	if($_ques==$_answ){
		_alert_back('密码提示与密码回答不能一样！');
	}
	return _mysql_string(sha1($_answ));
}
//性别验证
function _check_sex($_string){
	return _mysql_string($_string);
}
//头像验证
function _check_face($_string){
	return _mysql_string($_string);
}
//邮件验证
function _check_email($_string){
	if(!empty($_string)){
		if(!preg_match('/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/',$_string)){
			_alert_back('Mail format error!');
		}
	}
	return _mysql_string($_string);
}
//qq验证
function _check_qq($_string){
	if(empty($_string)){
		return null;
	}else{
		if(!preg_match('/^[1-9]{1}[\d]{4,9}$/',$_string)){
			_alert_back('QQ长度错误！');
		}
	}
	return _mysql_string($_string);
}
//网址验证
function _check_url($_string){
	if(empty($_string)||($_string=='http://')){
		_alert_back('URL cannot be empty!');
	}else{
		if(!preg_match('/^http(s)?:\/\/(\w+\.)?[\w\-\.]+(\.\w+)+$/',$_string)){
			_alert_back('URL format error!');
		}
	}
	return _mysql_string($_string);
}
//消息内容验证
function _check_content($_string){
	if (mb_strlen($_string,'utf-8')>200) {
		_alert_back('消息内容太长！');
	}
	return $_string;
}
//帖子标题验证
function _check_post_title($_string){
	if (mb_strlen($_string,'utf-8')>40) {
		_alert_back('帖子标题内容不能大于40位！');
	}
	return $_string;
}
//帖子内容验证
function _check_post_content($_string){
	if (mb_strlen($_string,'utf-8')<1) {
		_alert_back('帖子标题内容不能为空！');
	}
	return $_string;
}
//个性签名验证
function _check_autograph($_string){
	if (mb_strlen($_string,'utf-8')>200) {
		_alert_back('个性签名不能大于200位！');
	}
	return $_string;
}
//相册名验证
function _check_dir_name($_string){
	if(mb_strlen($_string,'utf-8')<2||mb_strlen($_string,'utf-8')>20){
		_alert_back('Name length cannot be less than 2 bits or greater than 20 bits!');
	}
	return $_string;
}
//相册密码验证
function _check_dir_passward($_string){
	if(strlen($_string)<6){
		_alert_back('密码长度不能小于6位！');
	}
	return sha1($_string);
}
//相册图片地址验证
function _check_photo_name($_string){
	if (empty($_string)) {
		_alert_back('Picture address cannot be empty!');
	}
	return $_string;
}
?>