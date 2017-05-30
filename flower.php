<?php 
/*
*Author:白小七
*Date:2017-5-8
*/
session_start();
//定义常量，授权调用includes里面的文件
define('IN_TG', true);
//定义常量，用来指定本页的内容
define('SCRIPT','flower');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';
//判断是否登录
if (!isset($_COOKIE['username'])) {
	_alert_close('PLEASE LOGIN!');
}
//送花
if ($_GET['action']=='send') {
	_check_code($_POST['code'],$_SESSION['code']);
	if (!!$_rows=_fetch_array("SELECT tg_uniqid FROM tg_user WHERE tg_username='{$_COOKIE['username']}' LIMIT 1")) {
		//防止cookie伪造,比对数据库唯一标识符与cookie中存的一致
		_uniqid($_rows['tg_uniqid'],$_COOKIE['uniqid']);
	}
	//引入验证文件
	include ROOT_PATH.'includes/check.func.php';
	$_clean=array();
	$_clean['touser']=$_POST['touser'];
	$_clean['fromuser']=$_COOKIE['username'];
	$_clean['flower']=$_POST['flower'];
	$_clean['content']=_check_content($_POST['content']);
	$_clean=_mysql_string($_clean);
	_query("INSERT INTO tg_flower (tg_touser,tg_fromuser,tg_flower,tg_content,tg_date) VALUES ('{$_clean['touser']}','{$_clean['fromuser']}','{$_clean['flower']}','{$_clean['content']}',NOW())");
	if (_affected_rows()==1) {
		_close();
		_alert_close('SEND FLOWER SUCCESS!');
	}else{
		_close();
		_alert_back('SEND FLOWER FAIL!');
	}
}
//获取数据
if (isset($_GET['id'])) {
	if (!!$_rows=_fetch_array("SELECT tg_username FROM tg_user WHERE tg_id='{$_GET['id']}' LIMIT 1")) {
		$_html=array();
		$_html['touser']=$_rows['tg_username'];
		$_html=_html($_html);
	}
}else{
	_alert_close('NOT HAS THE USER!');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php require ROOT_PATH.'includes/title.inc.php' ?>
	<script type="text/javascript" src="js/code.js"></script>
	<script type="text/javascript" src="js/message.js"></script>
</head>
<body>
	<div id="message">
		<h3>送花</h3>
		<form method="post" action="?action=send">
			<dl>
				<dd>TO：
					<input type="text" name="touser" class="text" readonly="readonly" value="<?php echo $_html['touser']; ?>">
					<img src="images/flower.png" alt="鲜花">
					<select name="flower">
					<?php 
						foreach (range(1,100) as $_num) {
							echo '<option value="'.$_num.'">+'.$_num.'朵</option>';
						}
					?>
					</select>
				</dd>
				<dd><textarea name="content">欧巴，超级稀饭你吆~~~~</textarea></dd>
				<dd>验&nbsp;证&nbsp;码&nbsp;：<input type="text" name="code" class="text code"><img src="code.php" alt="验证码" id="code"><input type="submit" class="submit" value="送花"></dd>
			</dl>
		</form>
	</div>
</body>
</html>