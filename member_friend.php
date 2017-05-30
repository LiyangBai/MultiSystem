<?php
/*
*Author:白小七
*Date:2017-5-8
*/
session_start();
//定义常量，授权调用includes里面的文件
define('IN_TG',true);
//定义常量，用来指定本页的内容
define('SCRIPT','member_friend');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';
//判断是否登录
if (!isset($_COOKIE['username'])) {
	_alert_back('PLEASE LOGIN!');
}
//验证好友
if ($_GET['action']=='check'&&$_GET['id']) {
	if (!!$_rows=_fetch_array("SELECT tg_uniqid FROM tg_user WHERE tg_username='{$_COOKIE['username']}' LIMIT 1")) {
		//防止cookie伪造,比对数据库唯一标识符与cookie中存的一致
		_uniqid($_rows['tg_uniqid'],$_COOKIE['uniqid']);
		//修改状态
		_query("UPDATE tg_friend SET tg_state=1 WHERE tg_id='{$_GET['id']}'");
		if (_affected_rows()==1) {
		_close();
		_location('FRIEND CHECK SUCCESS!','member_friend.php');
	}else{
		_close();
		_alert_back('FRIEND CHECK FAIL!');
	}
	}else{
		_alert_back('CHECH FAIL!');
	}
}
//批量删除
if ($_GET['action']=='delete'&&isset($_POST['ids'])) {
	$_clean=array();
	$_clean['ids']=_mysql_string(implode(',',$_POST['ids']));
	if (!!$_rows=_fetch_array("SELECT tg_uniqid FROM tg_user WHERE tg_username='{$_COOKIE['username']}' LIMIT 1")) {
		//防止cookie伪造,比对数据库唯一标识符与cookie中存的一致
		_uniqid($_rows['tg_uniqid'],$_COOKIE['uniqid']);
		_query("DELETE FROM tg_friend WHERE tg_id IN ({$_clean['ids']})");
	}
	if (_affected_rows()) {
		_close();
		_location('SUCCESS!','member_friend.php');
	}else{
		_close();
		_alert_back('FAIL!');
	}
}
//分页
_page("SELECT tg_id FROM tg_friend WHERE tg_fromuser='{$_COOKIE['username']}' OR tg_touser='{$_COOKIE['username']}'",8);
//从数据库中获取数据结果集
$_result=_query("SELECT tg_id,tg_fromuser,tg_touser,tg_content,tg_state,tg_date FROM tg_friend WHERE tg_fromuser='{$_COOKIE['username']}' OR tg_touser='{$_COOKIE['username']}' ORDER BY tg_date DESC LIMIT $_pagenum,$_pagesize");
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
			require ROOT_PATH.'includes/member.inc.php';
		?>
		<div id="member_main">
			<h2>好友设置中心</h2>
			<form method="post" action="?action=delete">
				<table>
					<tr><th>好友</th><th>请求内容</th><th>时间</th><th>状态</th><th>操作</th></tr>
					<?php 
						$_html=array();
						while (!!$_rows=_fetch_array_list($_result,MYSQL_ASSOC)) {
							$_html['id']=$_rows['tg_id'];
							$_html['touser']=$_rows['tg_touser'];
							$_html['fromuser']=$_rows['tg_fromuser'];
							$_html['content']=$_rows['tg_content'];
							$_html['state']=$_rows['tg_state'];
							$_html['date']=$_rows['tg_date'];
							$_html=_html($_html);
							if ($_html['fromuser']==$_COOKIE['username']) {
								$_html['friend']=$_html['touser'];
								if (empty($_html['state'])) {
									$_html['html_state']='<span style="color:red;">对方未验证</span>';
								}else{
									$_html['html_state']='<span style="color:green;">通过</span>';	
								}
							}else if ($_html['touser']==$_COOKIE['username']) {
								$_html['friend']=$_html['fromuser'];
								if (empty($_html['state'])) {
									$_html['html_state']='<a href="?action=check&id='.$_html['id'].'" style="color:blue;">你未验证</a>';
								}else{
									$_html['html_state']='<span style="color:green;">通过</span>';	
								}
							}
							
					?>
					<tr><td><?php echo $_html['friend']; ?></td><td class="content" title="<?php echo $_html['content']; ?>"><?php echo _title($_html['content'],14); ?></td><td><?php echo $_html['date']; ?></td><td><?php echo $_html['html_state']; ?></td><td><input type="checkbox" name="ids[]" value="<?php echo $_html['id']; ?>"></td></tr>	
					<?php }
						_free_result($_result);
						//设置分页类型,1表示数字分页,2表示文本分页
					?>
					<tr id="button"><td colspan="5"><label for="all">全选</label><input type="checkbox" id="all" name="chekall"><input type="submit" name="submit" value="批量删除"></td></tr>
				</table>
			</form>
			<?php  _paging(2); ?>
		</div>
	</div>
	<?php 
		require ROOT_PATH.'includes/footer.inc.php';
	?>
</body>
</html>