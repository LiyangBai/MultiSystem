<?php 
/*
*Author:白小七
*Date:2017-5-8
*/
session_start();
//定义常量，授权调用includes里面的文件
define('IN_TG', true);
//定义常量，用来指定本页的内容
define('SCRIPT','photo_add_img');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';
//是否登录
if (!$_COOKIE['username']) {
	_alert_back('Please login!');
}
//接收图片
if($_GET['action']=='add_img'){
	if (!!$_rows=_fetch_array("SELECT tg_uniqid FROM tg_user WHERE tg_username='{$_COOKIE['username']}' LIMIT 1")) {
		//防止cookie伪造,比对数据库唯一标识符与cookie中存的一致
		_uniqid($_rows['tg_uniqid'],$_COOKIE['uniqid']);
		//引入验证文件
		include ROOT_PATH.'includes/check.func.php';
		$_clean=array();
		$_clean['name']=_check_dir_name($_POST['name']);
		$_clean['url']=_check_photo_name($_POST['url']);
		$_clean['content']=$_POST['content'];
		$_clean['sid']=$_POST['sid'];
		$_clean=_mysql_string($_clean);
		//写入数据库
		_query("INSERT INTO tg_photo (tg_name,tg_url,tg_content,tg_sid,tg_username,tg_date) VALUES ('{$_clean['name']}','{$_clean['url']}','{$_clean['content']}','{$_clean['sid']}','{$_COOKIE['username']}',NOW())");
		//是否写入数据成功
		if (_affected_rows()==1) {
			_close();
			_location('Uploaded picture success!','photo_show.php?id='.$_clean['sid']);
		}else{
			_close();
			_alert_back('Uploaded picture fail!');
		}
	}else{
		_alert_back('ILLEGAL ACCESS!');
	}
}
//判断ID
if (isset($_GET['id'])) {
	if (!!$_rows=_fetch_array("SELECT tg_id,tg_dir FROM tg_dir WHERE tg_id='{$_GET['id']}' LIMIT 1")) {
		$_html=array();
		$_html['id']=$_rows['tg_id'];
		$_html['dir']=$_rows['tg_dir'];
		$_html=_html($_html);
	}else{
		_alert_back('Album does not exist!');
	}
}else{
	_alert_back('ILLEGAL ACCESS!');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php require ROOT_PATH.'includes/title.inc.php' ?>
	<script type="text/javascript" src="js/photo_add_img.js"></script>
</head>
<body>
	<?php 
		require ROOT_PATH.'includes/header.inc.php';
	?>
	<div id="photo">
		<h2>上传图片</h2>
		<form method="post" action="?action=add_img">
		<input type="hidden" name="sid" value="<?php echo $_html['id']; ?>">
			<dl>
				<dd>相册名称：<input type="text" name="name" class="text"></dd>
				<dd>图片地址：<input type="text" name="url" id="url" readonly="readonly" class="text"><a href="javascript:;" id="up" title="<?php echo $_html['dir']; ?>">上传</a></dd>
				<dd>图片简介：<textarea name="content"></textarea></dd>
				<dd><input type="submit" class="submit" value="添加图片"></dd>
			</dl>
		</form>
	</div>
	<?php 
		require ROOT_PATH.'includes/footer.inc.php';
	?>
</body>
</html>