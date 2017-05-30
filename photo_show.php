<?php 
/*
*Author:白小七
*Date:2017-5-8
*/
session_start();
//定义常量，授权调用includes里面的文件
define('IN_TG', true);
//定义常量，用来指定本页的内容
define('SCRIPT','photo_show');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';
//删除图片
if ($_GET['action']=='delete'&&isset($_GET['id'])) {
	if (!!$_rows=_fetch_array("SELECT tg_uniqid FROM tg_user WHERE tg_username='{$_COOKIE['username']}' LIMIT 1")) {
		//防止cookie伪造,比对数据库唯一标识符与cookie中存的一致
		_uniqid($_rows['tg_uniqid'],$_COOKIE['uniqid']);
		//取得图片发布者
		if (!!$_rows=_fetch_array("SELECT tg_id,tg_sid,tg_username,tg_url FROM tg_photo WHERE tg_id='{$_GET['id']}' LIMIT 1")) {
			$_html=array();
			$_html['id']=$_rows['tg_id'];
			$_html['sid']=$_rows['tg_sid'];
			$_html['username']=$_rows['tg_username'];
			$_html['url']=$_rows['tg_url'];
			$_html=_html($_html);
			if ($_html['username']==$_COOKIE['username']||isset($_SESSION['admin'])){
				//删除数据库图片
				_query("DELETE FROM tg_photo WHERE tg_id='{$_html['id']}'");
				//是否删除成功
				if (_affected_rows()==1) {
					//删除图片物理地址
					if (file_exists($_html['url'])) {
						unlink($_html['url']);
					}else{
						_alert_back('Not in disk!');
					}
					_close();
					_location('Delete success!','photo_show.php?id='.$_html['sid']);
				}else{
					_close();
					_alert_back('Delete failed!');
				}				
			}else{
				_alert_back('No permissions!');
			}
		}else{
			_alert_back('Picture not exist!');
		}
	}else{
		_alert_back('ILLEGAL ACCESS!');
	}
}
//取出数据
if (isset($_GET['id'])) {
	if (!!$_rows=_fetch_array("SELECT tg_id,tg_name,tg_type FROM tg_dir WHERE tg_id='{$_GET['id']}' LIMIT 1")) {
		$_dirhtml=array();
		$_dirhtml['id']=$_rows['tg_id'];
		$_dirhtml['name']=$_rows['tg_name'];
		$_dirhtml['type']=$_rows['tg_type'];
		$_dirhtml=_html($_dirhtml);
		//判断密码
		if ($_POST['passward']) {
			if (!!$_rows=_fetch_array("SELECT tg_id FROM tg_dir WHERE tg_passward='".sha1($_POST['passward'])."' LIMIT 1")) {
				//生成cookie
				setcookie('photo'.$_dirhtml['name'],$_dirhtml['name']);
				//重定向
				_location(null,'photo_detail.php?id='.$_dirhtml['id']);
			}else{
				_alert_back('Passward error!');
			}
		}
	}else{
		_alert_back('Album does not exist!');
	}
}else{
	_alert_back('ILLEGAL ACCESS!');
}
//全局id做带参分页
$_id='id='.$_dirhtml['id'];
$_percent=0.3;
//分页
_page("SELECT tg_id FROM tg_photo WHERE tg_sid='{$_dirhtml['id']}'",$_system['photo']);
//从数据库中获取数据结果集
$_result=_query("SELECT tg_id,tg_username,tg_name,tg_url,tg_readcount,tg_commentcount 
				FROM tg_photo WHERE tg_sid='{$_dirhtml['id']}' 
				ORDER BY tg_date DESC LIMIT $_pagenum,$_pagesize");
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
	<div id="photo">
		<h2><?php echo $_dirhtml['name']; ?></h2>
		<?php 
			if (empty($_dirhtml['type'])||$_COOKIE['photo'.$_dirhtml['name']]==$_dirhtml['name']||isset($_SESSION['admin'])) {
				$_html=array();
				while (!!$_rows=_fetch_array_list($_result,MYSQL_ASSOC)) {
					$_html['id']=$_rows['tg_id'];
					$_html['username']=$_rows['tg_username'];
					$_html['name']=$_rows['tg_name'];
					$_html['url']=$_rows['tg_url'];
					$_html['readcount']=$_rows['tg_readcount'];
					$_html['commentcount']=$_rows['tg_commentcount'];
					$_html=_html($_html);
		?>
		<dl>
			<dt><a href="photo_detail?id=<?php echo $_html['id']; ?>"><img src="thumbnail.php?filename=<?php echo $_html['url']; ?>&percent=<?php echo $_percent; ?>"></a></dt>
			<dd><a href="photo_detail?id=<?php echo $_html['id']; ?>"><?php echo $_html['name']; ?></a></dd>
			<dd>浏览(<strong><?php echo $_html['readcount'];?></strong>)评论(<strong><?php echo $_html['commentcount'];?></strong>)</dd>
			<dd>上传者:<?php echo $_html['username']; ?></dd>
			<?php
				if ($_html['username']==$_COOKIE['username']||isset($_SESSION['admin'])){?>
			<dd><a href="photo_show.php?action=delete&id=<?php echo $_html['id']; ?>">删除</a></dd>	
			<?php } ?>
		</dl>
		<?php } 
			_free_result($_result);
			_paging(1);
		?>
		<p>
			<a href="photo_add_img.php?id=<?php echo $_dirhtml['id']; ?>">[上传图片]</a><a href="photo.php">[返回目录]</a>
		</p>
		<?php 
			}else{
				echo '<form method="post" action="photo_show.php?id='.$_dirhtml['id'].'">';
				echo '<p>请输入密码：<input type="passward" name="passward"><input type="submit" value="确认" style="margin-left:10px;"></p>';
				echo '</form>';
			}
		?>
	</div>
	<?php 
		require ROOT_PATH.'includes/footer.inc.php';
	?>
</body>
</html>