<?php 
/*
*Author:白小七
*Date:2017-5-8
*/
session_start();
//定义常量，授权调用includes里面的文件
define('IN_TG', true);
//定义常量，用来指定本页的内容
define('SCRIPT','photo_add_dir');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';
//是否管理员
_manage_login();
//添加目录
if($_GET['action']=='add_dir'){
	if (!!$_rows=_fetch_array("SELECT tg_uniqid FROM tg_user WHERE tg_username='{$_COOKIE['username']}' LIMIT 1")) {
		//防止cookie伪造,比对数据库唯一标识符与cookie中存的一致
		_uniqid($_rows['tg_uniqid'],$_COOKIE['uniqid']);
		//引入验证文件
		include ROOT_PATH.'includes/check.func.php';
		//接收数据
		$_clean=array();
		$_clean['name']= _check_dir_name($_POST['name']);
		$_clean['type']=$_POST['type'];
		if (!empty($_clean['type'])) {
			$_clean['passward']=_check_dir_passward($_POST['passward']);
		}
		$_clean['content']=$_POST['content'];
		$_clean['dir']=time();
		$_clean=_mysql_string($_clean);
		//检查主目录是否存在
		if (!is_dir('photo')) {
			mkdir('photo',0777);  //新建目录,0777表示最高权限
		}
		//后在photo下创建相册目录
		if (!is_dir('photo/'.$_clean['dir'])) {
			mkdir('photo/'.$_clean['dir']);
		}
		//写入数据库
		if (empty($_clean['type'])) {
			_query("INSERT INTO tg_dir (tg_name,tg_type,tg_content,tg_dir,tg_date) VALUES ('{$_clean['name']}','{$_clean['type']}','{$_clean['content']}','photo/{$_clean['dir']}',NOW())");
		}else{
			_query("INSERT INTO tg_dir (tg_name,tg_type,tg_passward,tg_content,tg_dir,tg_date) VALUES ('{$_clean['name']}','{$_clean['type']}','{$_clean['passward']}','{$_clean['content']}','photo/{$_clean['dir']}',NOW())");
		}
		//是否添加成功
		if (_affected_rows()==1) {
			_close();
			_location('Add success!','photo.php');
		}else{
			_close();
			_alert_back('Add fail!');
		}
	}else{
		_alert_back('ILLEGAL ACCESS!');
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php require ROOT_PATH.'includes/title.inc.php' ?>
	<script type="text/javascript" src="js/photo_add_dir.js"></script>
</head>
<body>
	<?php 
		require ROOT_PATH.'includes/header.inc.php';
	?>
	<div id="photo">
		<h2>相册目录</h2>
		<form method="post" action="?action=add_dir">
			<dl>
				<dd>相册名称：<input type="text" name="name" class="text"></dd>
				<dd>相册类型：
					<input type="radio" name="type" checked="checked" value="0">公开
					<input type="radio" name="type" value="1">私密</dd>
				<dd id="passward">相册密码：<input type="passward" name="passward" class="text" ></dd>
				<dd>相册简介：<textarea name="content"></textarea></dd>
				<dd><input type="submit" class="submit" value="添加相册"></dd>
			</dl>
		</form>
	</div>
	<?php 
		require ROOT_PATH.'includes/footer.inc.php';
	?>
</body>
</html>