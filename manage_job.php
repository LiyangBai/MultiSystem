<?php
/*
*Author:白小七
*Date:2017-5-8
*/
session_start();
//定义常量，授权调用includes里面的文件
define('IN_TG',true);
//定义常量，用来指定本页的内容
define('SCRIPT','manage_job');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';
//是否管理员
_manage_login();
//添加管理员
if($_GET['action']=='add'){
	if (!!$_rows=_fetch_array("SELECT tg_uniqid FROM tg_user WHERE tg_username='{$_COOKIE['username']}' LIMIT 1")) {
		//防止cookie伪造,比对数据库唯一标识符与cookie中存的一致
		_uniqid($_rows['tg_uniqid'],$_COOKIE['uniqid']);
		$_clean=array();
		$_clean['username']=$_POST['manage'];
		$_clean=_mysql_string($_clean);
		//添加管理员
		_query("UPDATE tg_user SET tg_level=1 WHERE tg_username='{$_clean['username']}'");
		//是否添加成功
		if (_affected_rows()==1) {
			_close();
			_location('Add success!','manage_job.php');
		}else{
			_close();
			_alert_back('Add fail.The user name does not exist!');
		}
	}
}
//辞职
if($_GET['action']=='job'&&isset($_GET['id'])){
	if (!!$_rows=_fetch_array("SELECT tg_uniqid FROM tg_user WHERE tg_username='{$_COOKIE['username']}' LIMIT 1")) {
		//防止cookie伪造,比对数据库唯一标识符与cookie中存的一致
		_uniqid($_rows['tg_uniqid'],$_COOKIE['uniqid']);
		//写入数据库
		_query("UPDATE tg_user SET tg_level=0 WHERE tg_id='{$_GET['id']}' AND tg_username='{$_COOKIE['username']}'");
		//是否修改成功
		if (_affected_rows()==1) {
			_close();
			_location('Modify success!','manage_job.php');
		}else{
			_close();
			_alert_back('Modify fail.The user name does not exist!');
		}
	}
}
//分页
_page("SELECT tg_id FROM tg_user WHERE tg_level=1",20);
//从数据库中获取数据结果集
$_result=_query("SELECT tg_id,tg_username,tg_email,tg_reg_time FROM tg_user WHERE tg_level=1 ORDER BY tg_reg_time DESC LIMIT $_pagenum,$_pagesize");
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php require ROOT_PATH.'includes/title.inc.php' ?>
	<script type="text/javascript" src="js/member_message.js"></script>
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
			<h2>会员列表中心</h2>
			<table>
				<tr><th>ID</th><th>会员名</th><th>邮件</th><th>注册时间</th><th>操作</th></tr>
				<?php 
					$_html=array();
					while (!!$_rows=_fetch_array_list($_result,MYSQL_ASSOC)) {
						$_html['id']=$_rows['tg_id'];
						$_html['username']=$_rows['tg_username'];
						$_html['email']=$_rows['tg_email'];
						$_html['reg_time']=$_rows['tg_reg_time'];
						$_html=_html($_html);
						if ($_COOKIE['username']==$_html['username']) {
							$_html['job_html']='<a href="manage_job.php?action=job&id='.$_html['id'].'">辞职</a>';
						}else{
							$_html['job_html']='没有权限';
						}
				?>
				<tr><td><?php echo $_html['id']; ?></td><td><?php echo $_html['username']; ?></td><td><?php echo $_html['email']; ?></td><td><?php echo $_html['reg_time']; ?></td><td>[<?php echo $_html['job_html']; ?>]</td></tr>
				<?php }?>	
			</table>
			<form method="post" action="?action=add">
				<input type="text" name="manage" class="text">
				<input type="submit" value="添加管理员" class="submit">
			</form>
			<?php 
				_free_result($_result);
				//设置分页类型,1表示数字分页,2表示文本分页
				_paging(1);
			?>		
		</div>
	</div>
	<?php 
		require ROOT_PATH.'includes/footer.inc.php';
	?>
</body>
</html>