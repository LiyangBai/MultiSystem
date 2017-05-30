<?php 
/*
*Author:白小七
*Date:2017-5-8
*/
session_start();
//定义常量，授权调用includes里面的文件
define('IN_TG', true);
//定义常量，用来指定本页的内容
define('SCRIPT','photo_modify_dir');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';
//是否管理员
_manage_login();
//修改
if ($_GET['action']=='modify') {
	if (!!$_rows=_fetch_array("SELECT tg_uniqid FROM tg_user WHERE tg_username='{$_COOKIE['username']}' LIMIT 1")) {
		//防止cookie伪造,比对数据库唯一标识符与cookie中存的一致
		_uniqid($_rows['tg_uniqid'],$_COOKIE['uniqid']);
		//引入验证文件
		include ROOT_PATH.'includes/check.func.php';
		//接收数据
		$_clean=array();
		$_clean['id']=$_POST['id'];
		$_clean['name']= _check_dir_name($_POST['name']);
		$_clean['type']=$_POST['type'];
		if (!empty($_clean['type'])) {
			$_clean['passward']=_check_dir_passward($_POST['passward']);
		}
		$_clean['content']=$_POST['content'];
		$_clean['face']=$_POST['face'];
		$_clean=_mysql_string($_clean);
		//写入数据库
		if (empty($_clean['type'])) {
			_query("UPDATE tg_dir SET tg_name='{$_clean['name']}',tg_face='{$_clean['face']}',tg_passward=NULL,tg_content='{$_clean['content']}',tg_type='{$_clean['type']}'WHERE tg_id='{$_clean['id']}' LIMIT 1");
		}else{
			_query("UPDATE tg_dir SET tg_name='{$_clean['name']}',tg_face='{$_clean['face']}',tg_passward='{$_clean['passward']}',tg_content='{$_clean['content']}',tg_type='{$_clean['type']}'WHERE tg_id='{$_clean['id']}' LIMIT 1");
		}
		//是否添加成功
		if (_affected_rows()==1) {
			_close();
			_location('Modify success!','photo.php');
		}else{
			_close();
			_alert_back('Modify fail!');
		}
	}else{
		_alert_back('ILLEGAL ACCESS!');
	}	
}
//读出数据
if (isset($_GET['id'])) {
	if (!!$_rows=_fetch_array("SELECT tg_id,tg_name,tg_type,tg_content,tg_face FROM tg_dir WHERE tg_id='{$_GET['id']}' LIMIT 1")) {
		$_html=array();
		$_html['id']=$_rows['tg_id'];
		$_html['name']=$_rows['tg_name'];
		$_html['type']=$_rows['tg_type'];
		$_html['content']=$_rows['tg_content'];
		$_html['face']=$_rows['tg_face'];
		$_html=_html($_html);
	}else{
		_alert_back('ALBLUM NOT EXIST!');
	}
}else{
	_alert_back('READ DATA FAIL!');
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
		<h2>修改相册目录</h2>
		<form method="post" action="?action=modify">
			<dl>
				<dd>相册名称：<input type="text" name="name" class="text" value="<?php echo $_html['name']; ?>"></dd>
				<dd>相册类型：
					<input type="radio" name="type" value="0" <?php if ($_html['type']==0) {
						echo 'checked="checked"';} ?>>公开
					<input type="radio" name="type" value="1" <?php if ($_html['type']==1) {
						echo 'checked="checked"';} ?>>私密</dd>
				<dd id="passward" <?php if ($_html['type']==1) echo 'style="display:block;"'?>>相册密码：<input type="passward" name="passward" class="text" ></dd>
				<dd>相册封面：<input type="text" name="face" class="text" value="<?php echo $_html['face']; ?>"></dd>
				<dd>相册简介：<textarea name="content"><?php echo $_html['content']; ?></textarea></dd>
				<dd><input type="submit" class="submit" value="修改相册"></dd>
			</dl>
			<input type="hidden" name="id" value="<?php echo $_html['id']; ?>">
		</form>
	</div>
	<?php 
		require ROOT_PATH.'includes/footer.inc.php';
	?>
</body>
</html>