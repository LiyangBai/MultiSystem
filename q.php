<?php 
/*
*Author:白小七
*Date:2017-5-8
*/
session_start();
//定义常量，授权调用includes里面的文件
define('IN_TG', true);
//定义常量，用来指定本页的内容
define('SCRIPT', 'q');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';
if (isset($_GET['num'])&&isset($_GET['path'])) {
	if (!is_dir(ROOT_PATH.$_GET['path'])) {
		_alert_back('ILLEGAL ACCESS!');
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
	<script type="text/javascript" src="js/qopener.js"></script>
</head>
<body>
	<div id="q">
		<h3>Q图选择</h3>
		<dl>
			<?php foreach (range(1,$_GET['num']) as $_num) {?>
			<dd><img src="<?php echo $_GET['path'].$_num; ?>.png" alt="<?php echo $_GET['path'].$_num; ?>.png" title="Q图<?php echo $_num;?>"></dd>
			<?php } ?>
		</dl>
	</div>
</body>
</html>