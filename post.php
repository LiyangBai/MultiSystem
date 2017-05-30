<?php 
/*
*Author:白小七
*Date:2017-5-8
*/
session_start();
//定义常量，授权调用includes里面的文件
define('IN_TG', true);
//定义常量，用来指定本页的内容
define('SCRIPT','post');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';
if (!isset($_COOKIE['username'])) {
	_location('YOU HAVET LOGGED,PLEASE LOGIN!','login.php');
}
//将帖子写入数据库
if ($_GET['action']=='post') {
	//防止恶意注册
	_check_code($_POST['code'],$_SESSION['code']);
	if (!!$_rows=_fetch_array("SELECT tg_uniqid,tg_post_time FROM tg_user WHERE tg_username='{$_COOKIE['username']}' LIMIT 1")) {
		//防止cookie伪造,比对数据库唯一标识符与cookie中存的一致
		_uniqid($_rows['tg_uniqid'],$_COOKIE['uniqid']);
		//限时判断
		_timed(time(),$_rows['tg_post_time'],$_system['post']);
		//引入验证文件
		include ROOT_PATH.'includes/check.func.php';
		//接收帖子内容
		$_clean=array();
		$_clean['username']=$_COOKIE['username'];
		$_clean['title']=_check_post_title($_POST['title']);
		$_clean['content']=_check_post_content($_POST['content']);
		$_clean['type']=$_POST['type'];
		$_clean=_mysql_string($_clean);
		//写入数据
		_query("INSERT INTO tg_artical (tg_username,tg_title,tg_type,tg_content,tg_date) VALUES ('{$_clean['username']}','{$_clean['title']}','{$_clean['type']}','{$_clean['content']}',NOW())");
		//是否写入数据成功
		if (_affected_rows()==1) {
			//获取新增ID
			$_clean['id']=_insert_id();
			$_clean['post_time']=time();
			_query("UPDATE tg_user SET tg_post_time='{$_clean['post_time']}' WHERE tg_username='{$_COOKIE['username']}'");
			_close();
			_session_destroy();
			_location('Post success!','artical.php?id='.$_clean['id']);
		}else{
			_close();
			_session_destroy();
			_alert_back('Post failed!');
		}
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php require ROOT_PATH.'includes/title.inc.php' ?>
	<script type="text/javascript" src="js/code.js"></script>
	<script type="text/javascript" src="js/post.js"></script>
</head>
<body>
	<?php 
		require ROOT_PATH.'includes/header.inc.php';
	?>
	<div id="post">
		<h2>发表帖子</h2>
		<form method="post" name="post" action="?action=post">
			<dl>
				<dt>请认真填写以下页面</dt>
				<dd>类&nbsp;&nbsp;&nbsp;型：
					<?php 
						foreach (range(1,16) as $_num) {
							if ($_num==1) {
								echo '&nbsp;<label for="type'.$_num.'"><input type="radio" id="type'.$_num.'" name="type" value="'.$_num.'" checked="checked"/>&nbsp;';
							}else{
								echo '&nbsp;<lable for="type'.$_num.'"><input type="radio" id="type'.$_num.'" name="type" value="'.$_num.'"/>&nbsp;';
							}
							echo '<img src="images/icon'.$_num.'.png" alt="类型"/></lable>';
							if ($_num==8) {
								echo '<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
							}
						}
					?>
				</dd>
				<dd>标&nbsp;&nbsp;&nbsp;题：<input type="text" name="title" class="text"> (*必填，2-40位)</dd>
				<dd id="q">贴&nbsp;&nbsp;&nbsp;图：<a href="###">Q图系列[1]</a>&nbsp;<a href="###">Q图系列[2]</a>&nbsp;<a href="###">Q图系列[3]</a></dd>
				<dd>
					<?php include ROOT_PATH.'includes/ubb.inc.php'; ?>
					<textarea name="content" rows="9"></textarea>
				</dd>
				<dd>				
					验&nbsp;证&nbsp;码&nbsp;：
					<input type="text" name="code" class="text code">
					<img src="code.php" alt="验证码" id="code">				
					<input type="submit" class="submit" value="发表">
				</dd>
			</dl>
		</form>
	</div>
	<?php 
		require ROOT_PATH.'includes/footer.inc.php';
	?>
</body>
</html>