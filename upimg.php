<?php 
/*
*Author:白小七
*Date:2017-5-8
*/
//定义常量，授权调用includes里面的文件
define('IN_TG', true);
//定义常量，用来指定本页的内容
define('SCRIPT','upimg');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';
//是否登录
if (!$_COOKIE['username']) {
	_alert_back('Please login!');
}
//上传图片
if($_GET['action']=='up'){
	if (!!$_rows=_fetch_array("SELECT tg_uniqid FROM tg_user WHERE tg_username='{$_COOKIE['username']}' LIMIT 1")) {
		//防止cookie伪造,比对数据库唯一标识符与cookie中存的一致
		_uniqid($_rows['tg_uniqid'],$_COOKIE['uniqid']);
		//设置上传图片类型
		$_files=array('image/jpeg','image/png','image/gif','image/jpg');
		//判断类型是否是数组中的
		if (is_array($_files)) {
			if (!in_array($_FILES['userfile']['type'],$_files)) {
				_alert_back('The picture suffix must be jpg(jpeg) or png or gif');
			}
		}
		//判断文件错误类型
		if ($_FILES['userfile']['error']>0) {
			switch ($_FILES['userfile']['error']) {
				case 1:
					_alert_back('File size exceeded!');
					break;
				case 1:
					_alert_back('File size exceeded!');
					break;
				case 1:
					_alert_back('Some files have been uploaded!');
					break;				
				default:
					_alert_back('No files uploaded!');
					break;
			}
			exit;
		}
		//判断配置大小
		if ($_FILES['userfile']['size']>1000000) {
			_alert_back('Uploaded temporary files must not exceed 1M!');
		}
		//截取文件后缀
		$_Suffix=explode('.',$_FILES['userfile']['name']);
		$_name=$_POST['dir'].'/'.time().'.'.$_Suffix[1];
		//移动文件
		if (is_uploaded_file($_FILES['userfile']['tmp_name'])) {
			if (!move_uploaded_file($_FILES['userfile']['tmp_name'],$_name)) {	
				_alert_back('Moved fail!');
			}else{
				echo "<script>alert('Uploaded success!');window.opener.document.getElementById('url').value='$_name';window.close();</script>";
			}
		}else{
			_alert_back('Temporary files uploaded do not exist!');
		}
	}else{
		_alert_back('ILLEGAL ACCESS!');
	}
}
//接收dir
if (!isset($_GET['dir'])) {
	_alert_back('ILLEGAL ACCESS!');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php require ROOT_PATH.'includes/title.inc.php' ?>
</head>
<body>
	<div id="upimg" style="padding: 20px;">
		<form method="post" action="?action=up" enctype="multipart/form-data">
			<input type="hidden" name="MAX_FILE_SIZE" value="1000000"/> 
			选择图片：<input type="file" name="userfile" style="border: 1px solid #333;" />
			<input type="hidden" name="dir" value="<?php echo $_GET['dir']; ?>" />
			<input type="submit" value="上传"/>
		</form>	
	</div>
</body>
</html>