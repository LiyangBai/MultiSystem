<?php
/*
*Author:白小七
*Date:2017-5-8
*/
session_start();
//定义常量，授权调用includes里面的文件
define('IN_TG',true);
//定义常量，用来指定本页的内容
define('SCRIPT','manage_member');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';
//是否管理员
_manage_login();
//分页
_page("SELECT tg_id FROM tg_user",20);
//从数据库中获取数据结果集
$_result=_query("SELECT tg_id,tg_username,tg_email,tg_reg_time FROM tg_user ORDER BY tg_reg_time DESC LIMIT $_pagenum,$_pagesize");
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
			<form method="post" action="?action=delete">
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
					?>
					<tr><td><?php echo $_html['id']; ?></td><td><?php echo $_html['username']; ?></td><td><?php echo $_html['email']; ?></td><td><?php echo $_html['reg_time']; ?></td><td>[<a href="manage_delete.php?action=del&id=<?php echo $_html['id']; ?>">删除</a>][<a href="manage_modify.php?id=<?php echo $_html['id']; ?>">修改</a>]</td></tr>
					<?php }?>	
				</table>
			</form>	
			<?php 
				_free_result($_result);
				//设置分页类型,1表示数字分页,2表示文本分页
				_paging(2);
			?>		
		</div>
	</div>
	<?php 
		require ROOT_PATH.'includes/footer.inc.php';
	?>
</body>
</html>