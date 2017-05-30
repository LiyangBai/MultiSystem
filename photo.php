<?php 
/*
*Author:白小七
*Date:2017-5-8
*/
session_start();
//定义常量，授权调用includes里面的文件
define('IN_TG', true);
//定义常量，用来指定本页的内容
define('SCRIPT','photo');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';
//删除目录
if ($_GET['action']=='delete'&&isset($_GET['id'])) {
	if (!!$_rows=_fetch_array("SELECT tg_uniqid FROM tg_user WHERE tg_username='{$_COOKIE['username']}' LIMIT 1")) {
		//防止cookie伪造,比对数据库唯一标识符与cookie中存的一致
		_uniqid($_rows['tg_uniqid'],$_COOKIE['uniqid']);
		if (!!$_rows=_fetch_array("SELECT tg_dir FROM tg_dir WHERE tg_id='{$_GET['id']}' LIMIT 1")) {
			$_html=array();
			$_html['url']=$_rows['tg_dir'];
			$_html=_html($_html);
			//删除磁盘目录
			if (file_exists($_html['url'])) {
				if (_delDir($_html['url'])) {
					//删除目录的数据库图片
					_query("DELETE FROM tg_photo WHERE tg_sid='{$_GET['id']}'");
					//删除数据库目录
					_query("DELETE FROM tg_dir WHERE tg_id='{$_GET['id']}'");
					_close();
					_location('Delete success!','photo.php');
				}else{
					_close();
					_alert_back('Delete failed!');
				}				
			}else{
				_alert_back('Not in disk!');
			}
		}else{
			_alert_back('Directory not exist!');
		}
	}else{
		_alert_back('ILLEGAL ACCESS!');
	}
}
//分页
_page("SELECT tg_id FROM tg_dir",$_system['photo']);
//从数据库中获取数据结果集
$_result=_query("SELECT tg_id,tg_name,tg_type,tg_face FROM tg_dir ORDER BY tg_date DESC LIMIT $_pagenum,$_pagesize");
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
		<h2>相册列表</h2>
		<?php 
			$_html=array();
			while (!!$_rows=_fetch_array_list($_result,MYSQL_ASSOC)) {
				$_html['id']=$_rows['tg_id'];
				$_html['name']=$_rows['tg_name'];
				$_html['type']=$_rows['tg_type'];
				$_html['face']=$_rows['tg_face'];
				$_html=_html($_html);
				if (empty($_html['type'])) {
					$_html['type_html']='[公开]';
				}else{
					$_html['type_html']='[私密]';
				}
				if (empty($_html['face'])) {
					$_html['face_html']='';
				}else{
					$_html['face_html']='<img src="'.$_html['face'].'" alt="'.$_html['name'].'">';
				}
				//统计相册图片数量
				$_html['photo']=_fetch_array("SELECT COUNT(*) AS count FROM tg_photo WHERE tg_sid='{$_html['id']}'");		
		?>
		<dl>
			<dt><a href="photo_show.php?id=<?php echo $_html['id']; ?>"><?php echo $_html['face_html']; ?></a></dt>
			<dd><a href="photo_show.php?id=<?php echo $_html['id']; ?>"><?php echo $_html['name']; ?><?php echo '<br/>'.$_html['type_html']; ?></a>(<?php echo $_html['photo']['count'] ?>)</dd>
			<?php if (isset($_SESSION['admin'])&&isset($_COOKIE['username'])) {?>
			<dd>[<a href="photo_modify_dir.php?id=<?php echo $_html['id']; ?>">修改</a>][<a href="photo.php?action=delete&id=<?php echo $_html['id']; ?>">删除</a>]</dd>
			<?php } ?>
		</dl>
		<?php } ?>
		<?php if (isset($_SESSION['admin'])&&isset($_COOKIE['username'])) {?>
		<p><a href="photo_add_dir.php">[添加目录]</a></p>
		<?php } ?>
	</div>
	<?php 
		require ROOT_PATH.'includes/footer.inc.php';
	?>
</body>
</html>