<?php 
/*
*Author:白小七
*Date:2017-5-8
*/
session_start();
//定义常量，授权调用includes里面的文件
define('IN_TG', true);
//定义常量，用来指定本页的内容
define('SCRIPT','blog');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';
//判断是否登录
if (!isset($_COOKIE['username'])) {
	_alert_back('PLEASE LOGIN!');
}
//分页
_page("SELECT tg_id FROM tg_user",$_system['blog']);
//从数据库中获取数据结果集
$_result=_query("SELECT tg_id,tg_username,tg_sex,tg_face FROM tg_user ORDER BY tg_reg_time DESC LIMIT $_pagenum,$_pagesize");
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php require ROOT_PATH.'includes/title.inc.php' ?>
	<script type="text/javascript" src="js/blog.js"></script>
</head>
<body>
	<?php 
		require ROOT_PATH.'includes/header.inc.php';
	?>
	<div id="blog">
		<h2>博友列表</h2>
		<?php 
			$_html=array();
			while (!!$_rows=_fetch_array_list($_result,MYSQL_ASSOC)) {
				$_html['id']=$_rows['tg_id'];
				$_html['username']=$_rows['tg_username'];
				$_html['sex']=$_rows['tg_sex'];
				$_html['face']=$_rows['tg_face'];
				$_html=_html($_html);
		?>
		<dl>
			<dd class="user"><?php echo $_html['username'];?>(<?php echo $_html['sex'];?>)</dd>
			<dt><img src="<?php echo $_html['face'];?>" alt="<?php echo $_html['username'];?>"></dt>
			<dd class="message"><a href="" name="message" title="<?php echo $_html['id']; ?>">发消息</a></dd>
			<dd class="friend"><a href="" name="friend" title="<?php echo $_html['id']; ?>">加为好友</a></dd>
			<dd class="guest">写留言</dd>
			<dd class="flower"><a href="" name="flower" title="<?php echo $_html['id']; ?>">给他送花</a></dd>
		</dl>
		<?php }
			_free_result($_result);
			//设置分页类型,1表示数字分页,2表示文本分页
			_paging(1);
		?>
	</div>
	<?php 
		require ROOT_PATH.'includes/footer.inc.php';
	?>
</body>
</html>