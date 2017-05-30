<?php
/*
*Author:白小七
*Date:2017-5-8
*/
session_start();
//定义常量，授权调用includes里面的文件
define('IN_TG',true);
//定义常量，用来指定本页的内容
define('SCRIPT','member_message_detail');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';
//判断是否登录
if (!isset($_COOKIE['username'])) {
	_alert_back('PLEASE LOGIN!');
}
//删除短信
if ($_GET['action']=='delete'&&isset($_GET['id'])) {
	if (!!$_rows1=_fetch_array("SELECT tg_id FROM tg_message WHERE tg_id='{$_GET['id']}' LIMIT 1")){
		if (!!$_rows2=_fetch_array("SELECT tg_uniqid FROM tg_user WHERE tg_username='{$_COOKIE['username']}' LIMIT 1")) {
			//防止cookie伪造,比对数据库唯一标识符与cookie中存的一致
			_uniqid($_rows2['tg_uniqid'],$_COOKIE['uniqid']);
			//删除一条短信
			_query("DELETE FROM tg_message WHERE tg_id='{$_GET['id']}' LIMIT 1");
			//是否删除成功
			if (_affected_rows()==1) {
				_close();
				_location('SUCCESS!','member_message.php');
			}else{
				_close();
				_alert_back('FAIL!');
			}
		}else{
			_alert_back('ILLEGAL ACCESS!');
		}
	}else{
		_alert_back('THE MESSAGE NOT EXIST!');
	}
}
//处理信息页面的id
if (isset($_GET['id'])) {
	if (!!$_rows=_fetch_array("SELECT tg_id,tg_touser,tg_content,tg_state,tg_date FROM tg_message WHERE tg_id='{$_GET['id']}' LIMIT 1")){
		$_html=array();
		if (empty($_rows['tg_state'])) {
			_query("UPDATE tg_message SET tg_state=1 WHERE tg_id='{$_GET['id']}' LIMIT 1");
			if (!_affected_rows()) {
				_alert_back('ERROR!');
			}
		}
		$_html['id']=$_rows['tg_id'];
		$_html['touser']=$_rows['tg_touser'];
		$_html['content']=$_rows['tg_content'];
		$_html['date']=$_rows['tg_date'];
		$_html=_html($_html);
	}else{
		_alert_back("MESSAGE ERROR!");
	}
}else{
	_alert_close('ILLEGAL ACCESS!');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php require ROOT_PATH.'includes/title.inc.php' ?>
	<script type="text/javascript" src="js/member_message_detail.js"></script>
</head>
<body>
	<?php 
		require ROOT_PATH.'includes/header.inc.php';
	?>
	<div id="member">
		<?php 
			require ROOT_PATH.'includes/member.inc.php';
		?>
		<div id="member_main">
			<h2>短信详情</h2>
			<dl>
				<dd>收信人：<?php echo $_html['touser']; ?></dd>
				<dd>内&nbsp;&nbsp;&nbsp;容：<strong><?php echo $_html['content']; ?></strong></dd>
				<dd>时&nbsp;&nbsp;&nbsp;间：<?php echo $_html['date']; ?></dd>
				<dd class="button"><input type="button" value="返回列表" id="return"><input type="button" value="删除信息" name="<?php echo $_html['id']; ?>" id="delete"></dd>
			</dl>
		</div>
	</div>
	<?php 
		require ROOT_PATH.'includes/footer.inc.php';
	?>
</body>
</html>