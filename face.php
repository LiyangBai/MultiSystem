<?php 
/*
*Author:白小七
*Date:2017-5-8
*/
//定义常量，授权调用includes里面的文件
define('IN_TG', true);
//定义常量，用来指定本页的内容
define('SCRIPT', 'face');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php require ROOT_PATH.'includes/title.inc.php' ?>
	<script type="text/javascript" src="js/opener.js"></script>
</head>
<body>
	<div id="face">
		<h3>头像选择</h3>
		<dl>
			<?php foreach (range(1,9) as $_num) {?>
			<dd><img src="face/m0<?php echo $_num; ?>.jpg" alt="face/m0<?php echo $_num; ?>.jpg" title="头像<?php echo $_num;?>"></dd>
			<?php } ?>

		</dl>		
		<dl>
			<?php foreach (range(10,30) as $_num) {?>
			<dd><img src="face/m<?php echo $_num; ?>.jpg" alt="face/m<?php echo $_num; ?>.jpg" title="头像<?php echo $_num;?>"></dd>
			<?php } ?>

		</dl>	
	</div>
</body>
</html>