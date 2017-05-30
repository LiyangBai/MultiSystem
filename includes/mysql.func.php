<?php
/*
*Author:白小七
*Date:2017-5-8
*/
//防止恶意调用
if (!defined('IN_TG')) {
	exit('ILLEGAL ACCESS!');
}
//连接MySQL服务器
function _connect(){
	global $con; //全局变量
	if (!$con=@mysql_connect(DB_HOST,DB_USER,DB_PWD)) {
		exit('Mysql服务器连接错误！');
	}
}
//选择数据库
function _select_db(){
	if(!mysql_select_db(DB_NAME)){
		exit('数据库不存在！');
	}
}
//选择字符集
function _set_names(){
	if(!mysql_query("SET NAMES UTF8")){
		exit('字符集格式错误！');
	}
}
function _query($_sql){
	if(!$result=mysql_query($_sql)){
		exit('SQL ERROR!'.mysql_error());
	}
	return $result;
}
//取得结果集中的所有行
function _num_rows($_result){
	return mysql_num_rows($_result);
}
//获取一条数据集
function _fetch_array($_sql){
	return mysql_fetch_array(_query($_sql),MYSQL_ASSOC);
}
//返回指定数据集的多条数据
function _fetch_array_list($_result){
	return mysql_fetch_array($_result,MYSQL_ASSOC);
}
//判断用户是否已注册
function _is_repeat($_sql,$_info){
	if (_fetch_array($_sql)) {
		_alert_back($_info);
	}
}
//获取新增ID
function _insert_id(){
	return mysql_insert_id();
}
//关闭数据库
function _close(){
	if(!mysql_close()){
		exit('数据库关闭错误！');
	}
}
//操作表影响的行数
function _affected_rows(){
	return mysql_affected_rows();
}
//销毁结果集
function _free_result($_result){
	return mysql_free_result($_result);
}
?>