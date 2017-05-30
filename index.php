<?php 
/*
*Author:白小七
*Date:2017-5-8
*/
session_start();
//定义常量，授权调用includes里面的文件
define('IN_TG', true);
//定义常量，用来指定本页的内容
define('SCRIPT','index');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';//转换成绝对路径速度更快
//读取XML文件
$_html=_html(_get_xml('new.xml'));
//读取帖子列表
_page("SELECT tg_id FROM tg_artical WHERE tg_reid=0",$_system['artical']);
//从数据库中获取数据结果集
$_result=_query("SELECT tg_id,tg_title,tg_type,tg_readcount,tg_commentcount FROM tg_artical WHERE tg_reid=0 ORDER BY tg_date DESC LIMIT $_pagenum,$_pagesize");
//最新图片
if (!!$_photo=_fetch_array("SELECT tg_id,tg_name,tg_url FROM tg_photo 
					WHERE tg_sid IN (SELECT tg_id FROM tg_dir WHERE tg_type=0) 
					ORDER BY tg_date DESC LIMIT 1")) {
	$_photohtml=array();
	$_photohtml['id']=$_photo['tg_id'];
	$_photohtml['name']=$_photo['tg_name'];
	$_photohtml['url']=$_photo['tg_url'];
	$_photohtml=_html($_photohtml);
}else{
	echo '<span>没有公开图片！</span>';
}
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
	<div id="list">
		<h2>帖子列表</h2>
		<a href="post.php" class="post">发表帖子</a>
		<ul class="artical">
			<?php 
				$_htmllist=array();
				while (!!$_rows=_fetch_array_list($_result,MYSQL_ASSOC)) {
					$_htmllist['id']=$_rows['tg_id'];
					$_htmllist['type']=$_rows['tg_type'];
					$_htmllist['title']=$_rows['tg_title'];
					$_htmllist['readcount']=$_rows['tg_readcount'];
					$_htmllist['commentcount']=$_rows['tg_commentcount'];
					$_htmllist=_html($_htmllist);
					echo '<li class="icon'.$_htmllist['type'].'"><em>阅读数(<strong>'.$_htmllist['readcount'].'</strong>)评论数(<strong>'.$_htmllist['commentcount'].'</strong>)</em><a href="artical.php?id='.$_htmllist['id'].'">'._title($_htmllist['title'],20).'</a></li>';
				}
				_free_result($_result);
			?>
		</ul>
		<?php 
			if ($_result) {
				_paging(2);
			}
		?>
	</div>
	<div id="user">
		<h2>新进会员</h2>
		<dl>
			<dd class="user"><?php echo $_html['username']; ?>(<?php echo $_html['sex']; ?>)</dd>
			<dt><img src="<?php echo $_html['face']; ?>" alt="<?php echo $_html['username']; ?>"></dt>
			<dd class="message"><a href="" name="message" title="<?php echo $_html['id']; ?>">发消息</a></dd>
			<dd class="friend"><a href="" name="friend" title="<?php echo $_html['id']; ?>">加为好友</a></dd>
			<dd class="guest">写留言</dd>
			<dd class="flower"><a href="" name="flower" title="<?php echo $_html['id']; ?>">给他送花</a></dd>
			<dd class="email">邮件：<a href="mailto:<?php echo $_html['email']; ?>"><?php echo $_html['email']; ?></a></dd>
			<dd class="url">网址：<a href="<?php echo $_html['url']; ?>" target="_black"><?php echo $_html['url']; ?></a></dd>
		</dl>
	</div>
	<div id="pics">
		<h2>最新图片--<?php echo $_photohtml['name'] ?></h2>
		<a href="photo_detail?id=<?php echo $_photohtml['id'] ?>"><img src="thumbnail.php?filename=<?php echo $_photohtml['url']; ?>&percent=0.3" alt="<?php echo $_photohtml['name'] ?>"></a>
	</div>
	<?php 
		require ROOT_PATH.'includes/footer.inc.php';
	?>
</body>
</html>