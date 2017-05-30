<?php 
/*
*Author:白小七
*Date:2017-5-8
*/
session_start();
//定义常量，授权调用includes里面的文件
define('IN_TG', true);
//定义常量，用来指定本页的内容
define('SCRIPT','artical_subject_modify');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';
if (!isset($_COOKIE['username'])) {
	_location('YOU HAVET LOGGED,PLEASE LOGIN!','login.php');
}
//提交数据库
if ($_GET['action']=='modify') {
	_check_code($_POST['code'],$_SESSION['code']);
	if (!!$_rows=_fetch_array("SELECT tg_uniqid FROM tg_user WHERE tg_username='{$_COOKIE['username']}' LIMIT 1")) {
		//防止cookie伪造,比对数据库唯一标识符与cookie中存的一致
		_uniqid($_rows['tg_uniqid'],$_COOKIE['uniqid']);
		//引入验证文件
		include ROOT_PATH.'includes/check.func.php';
		//接收帖子内容
		$_clean=array();
		$_clean['id']=$_POST['id'];
		$_clean['title']=_check_post_title($_POST['title']);
		$_clean['content']=_check_post_content($_POST['content']);
		$_clean['type']=$_POST['type'];
		$_clean=_mysql_string($_clean);
		//写入数据
		_query("UPDATE tg_artical SET tg_title='{$_clean['title']}',tg_type='{$_clean['type']}',tg_content='{$_clean['content']}',tg_last_modify_date=NOW() WHERE tg_id='{$_clean['id']}'");
		//是否修改数据成功
		if (_affected_rows()==1) {
			_close();
			_location('Modify success!','artical.php?id='.$_clean['id']);
		}else{
			_close();
			_alert_back('Modify fail!','artical.php');
		}
	}
}
//修改帖子
if (isset($_GET['id'])) {
	if (!!$_rows=_fetch_array("SELECT tg_username,tg_type,tg_content,tg_title FROM tg_artical WHERE tg_reid=0 AND tg_id='{$_GET['id']}'")) {
		$_html=array();
		$_html['id']=$_GET['id'];
		$_html['username']=$_rows['tg_username'];
		$_html['type']=$_rows['tg_type'];
		$_html['content']=$_rows['tg_content'];
		$_html['title']=$_rows['tg_title'];
		$_html=_html($_html);
		if (!isset($_SESSION['admin'])) {
			if ($_html['username']!=$_COOKIE['username']) {
				_alert_back('NO PERMISSION TO MODIFY!');
			}
		}
	}else{
		_alert_back('THEME NOT EXIST!');
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
	<script type="text/javascript" src="js/code.js"></script>
	<script type="text/javascript" src="js/post.js"></script>
</head>
<body>
	<?php 
		require ROOT_PATH.'includes/header.inc.php';
	?>
	<div id="artical_subject_modify">
		<h2>修改帖子</h2>
		<form method="post" name="post" action="?action=modify">
		<input type="hidden" name="id" value="<?php echo $_html['id']; ?>">
			<dl>
				<dt>请认真修改以下页面</dt>
				<dd>类&nbsp;&nbsp;&nbsp;型：	
					<?php 
						foreach (range(1,16) as $_num) {
							if ($_num==$_html['type']) {
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
				<dd>标&nbsp;&nbsp;&nbsp;题：<input type="text" name="title" class="text" value="<?php echo $_html['title']; ?>"> (*必填，2-40位)</dd>
				<dd id="q">贴&nbsp;&nbsp;&nbsp;图：<a href="###">Q图系列[1]</a>&nbsp;<a href="###">Q图系列[2]</a>&nbsp;<a href="###">Q图系列[3]</a></dd>
				<dd>
					<?php include ROOT_PATH.'includes/ubb.inc.php'; ?>
					<textarea name="content" rows="9"><?php echo $_html['content']; ?></textarea>
				</dd>
				<dd>验&nbsp;证&nbsp;码&nbsp;：
					<input type="text" name="code" class="text code">
					<img src="code.php" alt="验证码" id="code">
					<input type="submit" class="submit" value="修改">
				</dd>
			</dl>
		</form>
	</div>
	<?php 
		require ROOT_PATH.'includes/footer.inc.php';
	?>
</body>
</html>