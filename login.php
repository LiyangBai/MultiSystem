<?php
/*
*Author:白小七
*Date:2017-5-8
*/
session_start();
//定义常量，授权调用includes里面的文件
define('IN_TG',true);
//定义常量，用来指定本页的内容
define('SCRIPT','login');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';
//登录状态
_login_state();
//登录
if ($_GET['action']=='login') {
	//防止恶意注册
	_check_code($_POST['code'],$_SESSION['code']);
	//引入验证文件
	include ROOT_PATH.'includes/login.func.php';
	$_clean=array();
	$_clean['username']=_check_username($_POST['username']);
	$_clean['passward']=_check_passward($_POST['passward']);
	$_clean['time']=_check_time($_POST['time']);
	//数据库验证
	if (!!$_rows=_fetch_array("SELECT tg_username,tg_uniqid,tg_level FROM tg_user WHERE tg_username='{$_clean['username']}' AND tg_passward='{$_clean['passward']}' AND tg_active='' LIMIT 1")) {
		//登录成功,记录信息
		_query("UPDATE tg_user SET tg_last_time=NOW(),tg_last_ip='{$_SERVER["REMOTE_ADDR"]}',tg_login_count=tg_login_count+1 WHERE tg_username='{$_rows['tg_username']}'");
		//_session_destroy();
		_setcookies($_rows['tg_username'],$_rows['tg_uniqid'],$_clean['time']);
		if ($_rows['tg_level']==1) {
			$_SESSION['admin']=$_rows['tg_username'];	
		}
		_close();
		_location('LOGIN SUCCESS!','member.php');
	}else{
		_close();
		_location('USERNAME ERROR OR NOT EXISTE!','login.php');
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php require ROOT_PATH.'includes/title.inc.php' ?>
	<script type="text/javascript" src="js/code.js"></script>
	<script type="text/javascript" src="js/login.js"></script>
</head>
<body>
	<?php 
		require ROOT_PATH.'includes/header.inc.php';
	?>
	<div id="login">
		<h2>登录</h2>
		<form method="post" name="login" action="login.php?action=login">
			<dl>
				<dd>用&nbsp;户&nbsp;名&nbsp;：<input type="text" name="username" class="text"> (*必填 至少两位)</dd>
				<dd>密&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;码：<input type="passward" name="passward" class="text"> (*必填 至少六位)</dd>
				<dd>记住密码：<input type="radio" name="time" value="0" checked="checked">不保留&nbsp;<input type="radio" name="time" value="1">一天&nbsp;<input type="radio" name="time" value="2">一周&nbsp;<input type="radio" name="time" value="3">一月</dd>
				<dd>验&nbsp;证&nbsp;码&nbsp;：<input type="text" name="code" class="text code"><img src="code.php" alt="验证码" id="code"></dd>
				<dd><input type="submit" class="button" value="登录"><input type="button" id="location" class="button" name="register" value="注册"></dd>
			</dl>
		</form>
	</div>
	<?php 
		require ROOT_PATH.'includes/footer.inc.php';
	?>
</body>
</html>