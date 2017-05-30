<?php 
/*
*Author:白小七
*Date:2017-5-8
*/
//定义常量，授权调用includes里面的文件
define('IN_TG', true);
//定义常量，用来指定本页的内容
define('SCRIPT','active');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';
//激活处理
if (!isset($_GET['active'])) {
	_alert_back('用户账户未激活！');
}
if (isset($_GET['action']) && isset($_GET['active']) && $_GET['action']=='ok') {
	$_active=_mysql_string($_GET['active']);
	if (_fetch_array("SELECT tg_active FROM tg_user WHERE tg_active='$_active'LIMIT 1")) {
		_query("UPDATE tg_user SET tg_active=null WHERE tg_active='$_active'LIMIT 1");
		if(_affected_rows()==1){
			_close();
			_location('Active success!','login.php');
		}else{
			_close();
			_location('Active fail!','register.php');
		}
	}else{
		_alert_back('The user is not activated!');
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php require ROOT_PATH.'includes/title.inc.php' ?>
</head>
<body>
	<?php 
		require ROOT_PATH.'includes/header.inc.php';
	?>
	<div id="active">
		<h2>激活账户</h2>
		<p>此页面模拟邮件激活，点击以下超链接激活您的账户！</p>
		<p><a href="active.php?action=ok&amp;active=<?php echo $_GET['active']; ?>"><?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']; ?>?action=ok&amp;active=<?php echo $_GET['active'] ?></a></p>
	</div>
	<?php 
		require ROOT_PATH.'includes/footer.inc.php';
	?>
</body>
</html>