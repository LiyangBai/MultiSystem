<?php
/*
*Author:白小七
*Date:2017-5-8
*/
session_start();
//定义常量，授权调用includes里面的文件
define('IN_TG',true);
//定义常量，用来指定本页的内容
define('SCRIPT','manage');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';
//是否管理员
_manage_login();
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
	<div id="member">
		<?php 
			require ROOT_PATH.'includes/manage.inc.php';
		?>
		<div id="member_main">
			<h2>后台首页</h2>
			<dl>
				<dd>•&nbsp;服务器主机名称：<?php echo $_SERVER['SERVER_NAME'];?></dd>
				<dd>•&nbsp;服务器版本：<?php echo $_ENV['OS'];?></dd>
				<dd>•&nbsp;通信协议名称/版本：<?php echo $_SERVER['SERVER_PROTOCOL'];?></dd>
				<dd>•&nbsp;服务器IP：<?php echo $_SERVER['SERVER_ADDR'];?></dd>
				<dd>•&nbsp;客户端IP：<?php echo $_SERVER['REMOTE_ADDR'];?></dd>
				<dd>•&nbsp;服务器端口：<?php echo $_SERVER['SERVER_PORT'];?></dd>
				<dd>•&nbsp;客户端端口：<?php echo $_SERVER['REMOTE_PORT'];?></dd>
				<dd>•&nbsp;管理员邮箱：<?php echo $_SERVER['SERVER_ADMIN'];?></dd>
				<dd>•&nbsp;HOST头部的名称：<?php echo $_SERVER['HTTP_HOST'];?></dd>
				<dd>•&nbsp;服务器主目录：<?php echo $_SERVER['DOCUMENT_ROOT'];?></dd>
				<dd>•&nbsp;服务器系统盘：<?php echo $_ENV['SystemRoot'];?></dd>
				<dd>•&nbsp;脚本执行的绝对路径：<?php echo $_ENV['SCRIPT_FILENAME'];?></dd>
				<dd>•&nbsp;Apache及PHP版本：<?php echo $_SERVER['SERVER_SOFTWARE'];?></dd>
			</dl>
		</div>
	</div>
	<?php 
		require ROOT_PATH.'includes/footer.inc.php';
	?>
</body>
</html>