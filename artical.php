<?php 
/*
*Author:白小七
*Date:2017-5-8
*/
session_start();
//定义常量，授权调用includes里面的文件
define('IN_TG', true);
//定义常量，用来指定本页的内容
define('SCRIPT','artical');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';
//处理精华帖
if ($_GET['action']=='nice'&&isset($_GET['id'])&&isset($_GET['switch'])) {
	if (!!$_rows=_fetch_array("SELECT tg_uniqid FROM tg_user WHERE tg_username='{$_COOKIE['username']}' LIMIT 1")) {
		//防止cookie伪造,比对数据库唯一标识符与cookie中存的一致
		_uniqid($_rows['tg_uniqid'],$_COOKIE['uniqid']);
		//设置或取消
		_query("UPDATE tg_artical SET tg_nice='{$_GET['switch']}' WHERE tg_id='{$_GET['id']}'");
		//是否写入数据成功
		if (_affected_rows()==1) {
			_close();
			_location('Operation SUCCESSS!','artical.php?id='.$_GET['id']);
		}else{
			_close();
			_alert_back('Operation FAIL!','artical.php');
		}
	}else{
		_alert_back('ILLEGAL ACCESS!');
	}
}
//处理回帖
if ($_GET['action']=='reartical') {
	_check_code($_POST['code'],$_SESSION['code']);
	if (!!$_rows=_fetch_array("SELECT tg_uniqid,tg_artical_time FROM tg_user WHERE tg_username='{$_COOKIE['username']}' LIMIT 1")) {
		//防止cookie伪造,比对数据库唯一标识符与cookie中存的一致
		_uniqid($_rows['tg_uniqid'],$_COOKIE['uniqid']);
		//限时判断
		_timed(time(),$_rows['tg_artical_time'],$_system['re']);
		//接收数据
		$_clean=array();
		$_clean['reid']=$_POST['reid'];
		$_clean['type']=$_POST['type'];
		$_clean['title']=$_POST['title'];
		$_clean['content']=$_POST['content'];
		$_clean['username']=$_COOKIE['username'];
		$_clean=_mysql_string($_clean);
		//写入数据库
		_query("INSERT INTO tg_artical (tg_reid,tg_title,tg_type,tg_content,tg_username,tg_date) VALUES ('{$_clean['reid']}','{$_clean['title']}','{$_clean['type']}','{$_clean['content']}','{$_clean['username']}',NOW())");
		//是否写入数据成功
		if (_affected_rows()==1) {
			$_clean['post_time']=time();
			_query("UPDATE tg_user SET tg_artical_time='{$_clean['post_time']}' WHERE tg_username='{$_COOKIE['username']}'");
			_query("UPDATE tg_artical SET tg_commentcount=tg_commentcount+1 WHERE tg_reid=0 AND tg_id='{$_clean['reid']}'");
			_close();
			_location('RETURNED SUCCESSS!','artical.php?id='.$_clean['reid']);
		}else{
			_close();
			_alert_back('RETURNED FAIL!','artical.php');
		}
	}else{
		_alert_back('ILLEGAL ACCESS!');
	}
}
//读出数据
if (isset($_GET['id'])) {
	if (!!$_rows=_fetch_array("
		SELECT tg_id,tg_username,tg_type,tg_content,tg_title,tg_readcount,tg_commentcount,tg_nice,tg_date,tg_last_modify_date 
		FROM tg_artical WHERE tg_reid=0 AND tg_id='{$_GET['id']}'")) {
		$_html=array();
		$_html['reid']=$_rows['tg_id'];
		$_html['username_subject']=$_rows['tg_username'];
		$_html['type']=$_rows['tg_type'];
		$_html['content']=$_rows['tg_content'];
		$_html['title']=$_rows['tg_title'];
		$_html['readcount']=$_rows['tg_readcount'];
		$_html['commentcount']=$_rows['tg_commentcount'];
		$_html['nice']=$_rows['tg_nice'];
		$_html['date']=$_rows['tg_date'];
		$_html['last_modify_date']=$_rows['tg_last_modify_date'];
		//累加阅读量
		_query("UPDATE tg_artical SET tg_readcount=tg_readcount+1 WHERE tg_id='{$_GET['id']}'");
		//读出用户信息
		if (!!$_rows=_fetch_array("
		SELECT tg_id,tg_sex,tg_face,tg_email,tg_url,tg_switch,tg_autograph FROM tg_user WHERE tg_username='{$_html['username_subject']}'")) {
			$_html['userid']=$_rows['tg_id'];
			$_html['sex']=$_rows['tg_sex'];
			$_html['face']=$_rows['tg_face'];
			$_html['email']=$_rows['tg_email'];
			$_html['url']=$_rows['tg_url'];
			$_html['switch']=$_rows['tg_switch'];
			$_html['autograph']=$_rows['tg_autograph'];
			$_html=_html($_html);
			//全局id做带参分页
			global $_id;
			$_id='id='.$_html['reid'].'&';
			//读取回帖
			_page("SELECT tg_id FROM tg_artical WHERE tg_reid='{$_html['reid']}'",5);
			$_result=_query("
				SELECT tg_username,tg_type,tg_title,tg_content,tg_date FROM tg_artical 
				WHERE tg_reid='{$_html['reid']}' ORDER BY tg_date ASC LIMIT $_pagenum,$_pagesize");
			//主题帖子修改
			if ($_html['username_subject']==$_COOKIE['username']||isset($_SESSION['admin'])) {
				$_html['subject_modify']='[<a href="artical_subject_modify.php?id='.$_html['reid'].'">修改</a>]';
			}
			//主题帖删除
			if ($_html['username_subject']==$_COOKIE['username']||isset($_SESSION['admin'])) {
				$_html['subject_delete']='[<a href="artical_delete.php?action=delete&id='.$_html['reid'].'">删除</a>]';
			}
			//回复楼主
			if ($_COOKIE['username']) {
					$_html['re']='<span>[<a href="#rea" name="re" title="回复1楼的'.$_html['username_subject'].'">回复</a>]</span>';
				}
			//读取修改信息
			if ($_html['last_modify_date']!='0000-00-00 00:00:00') {
				$_html['last_modify_date_str']='最近修改于'.$_html['last_modify_date'];
			}
			//个性签名
			if ($_html['switch']==1) {
				$_html['autograph_html']='<p class="autograph">个性签名：'.$_html['autograph'].'</p>';
			}
		}else{
			echo '<h4 style="text-align:center;padding-top:20px;">会员已被删除！</h4>';
		}
	}else{
		_alert_back('THEME NOT EXIST!');
	}
}else{
	_alert_back('READ DATA FAIL!');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php require ROOT_PATH.'includes/title.inc.php' ?>
	<script type="text/javascript" src="js/code.js"></script>
	<script type="text/javascript" src="js/artical.js"></script>
</head>
<body>
	<?php 
		require ROOT_PATH.'includes/header.inc.php';
	?>
	<div id="artical">
		<h2>帖子详情</h2>
		<?php 
			if ($_SESSION['admin']) {
				if (!empty($_html['nice'])) {	
		?>
		<img src="images/nice.png" alt="精华帖" class="nice">
		<?php } } ?>
		<?php if ($_html['readcount']>=50 && $_html['commentcount']>=20) {?>
		<img src="images/hot.png" alt="热帖" class="hot">
		<?php } if ($_page==1) {?>
		<div id="subject">
			<dl>
				<dd class="user"><?php echo $_html['username_subject']; ?>(<?php echo $_html['sex']; ?>)[楼主]</dd>
				<dt><img src="<?php echo $_html['face']; ?>" alt="<?php echo $_html['username_subject']; ?>"></dt>
				<dd class="message"><a href="javascript:;" name="message" title="<?php echo $_html['userid']; ?>">发消息</a></dd>
				<dd class="friend"><a href="javascript:;" name="friend" title="<?php echo $_html['userid']; ?>">加为好友</a></dd>
				<dd class="guest">写留言</dd>
				<dd class="flower"><a href="javascript:;" name="flower" title="<?php echo $_html['userid']; ?>">给他送花</a></dd>
				<dd class="email">邮件：<a href="mailto:<?php echo $_html['email']; ?>"><?php echo $_html['email']; ?></a></dd>
				<dd class="url">网址：<a href="<?php echo $_html['url']; ?>" target="_black"><?php echo $_html['url']; ?></a></dd>
			</dl>
			</dl>
			<div class="content">
				<div class="user">
					<span><?php echo $_html['subject_modify']; ?></span>
					<span><?php echo $_html['subject_delete']; ?></span>
					<p>
					<?php echo $_html['username_subject']; ?>|发表于：<?php echo $_html['date']; ?>|
					<?php if (empty($_html['nice'])) {?>
					<a href="artical.php?action=nice&switch=1&id=<?php echo $_html['reid']; ?>"> 设置精华帖</a>
					<?php }else{ ?>
					<a href="artical.php?action=nice&switch=0&id=<?php echo $_html['reid']; ?>"> 取消精华帖</a>
					<?php } ?>
					</p>
				</div>
				<h3><i class="ans">主题：<?php echo $_html['title']; ?></i><img src="images/icon<?php echo $_html['type']; ?>.png" alt="icon"><?php echo $_html['re']; ?></h3>
				<div class="detail"><?php echo ubb($_html['content']); ?><?php echo $_html['autograph_html']; ?></div>
				<div class="read">阅读量：(<?php echo $_html['readcount']; ?>) 评论量：(<?php echo $_html['commentcount']; ?>)<?php echo $_html['last_modify_date_str']; ?></div>
			</div>
		</div>
		<p class="line"></p>
		<?php } ?>
		<?php 
			$_i=1;
			while (!!$_rows=_fetch_array_list($_result,MYSQL_ASSOC)) {
				$_html['username']=$_rows['tg_username'];
				$_html['type']=$_rows['tg_type'];
				$_html['title']=$_rows['tg_title'];
				$_html['content']=$_rows['tg_content'];
				$_html['date']=$_rows['tg_date'];
				$_html=_html($_html);
				//读出用户信息
				if (!!$_rows=_fetch_array("
				SELECT tg_id,tg_sex,tg_face,tg_email,tg_url,tg_switch,tg_autograph 
				FROM tg_user WHERE tg_username='{$_html['username']}'")) {
					$_html['userid']=$_rows['tg_id'];
					$_html['sex']=$_rows['tg_sex'];
					$_html['face']=$_rows['tg_face'];
					$_html['email']=$_rows['tg_email'];
					$_html['url']=$_rows['tg_url'];
					$_html['switch']=$_rows['tg_switch'];
					$_html['autograph']=$_rows['tg_autograph'];
					$_html=_html($_html);
					//楼主沙发
					if ($_page==1&&$_i==1) {
						if ($_html['username']==$_html['username_subject']) {
							$_html['username_html']=$_html['username'].'(楼主)';
						}else{
							$_html['username_html']=$_html['username'].'(沙发)';
						}
					}else{
						$_html['username_html']=$_html['username'];
					}
				}else{
					echo '<h4 style="text-align:center;padding-top:20px;">会员已被删除！</h4>';
				}
				if ($_COOKIE['username']) {
					$_html['re']='<span>[<a href="#rea" name="re" title="回复'.($_i+($_page-1)*$_pagesize).'楼的'.$_html['username'].'">回复</a>]</span>';
				}
		?>

		<div class="re">
			<dl>
				<dd class="user"><?php echo $_html['username_html']; ?>(<?php echo $_html['sex']; ?>)</dd>
				<dt><img src="<?php echo $_html['face']; ?>" alt="<?php echo $_html['username']; ?>"></dt>
				<dd class="message"><a href="javascript:;" name="message" title="<?php echo $_html['userid']; ?>">发消息</a></dd>
				<dd class="friend"><a href="javascript:;" name="friend" title="<?php echo $_html['userid']; ?>">加为好友</a></dd>
				<dd class="guest">写留言</dd>
				<dd class="flower"><a href="javascript:;" name="flower" title="<?php echo $_html['userid']; ?>">给他送花</a></dd>
				<dd class="email">邮件：<a href="mailto:<?php echo $_html['email']; ?>"><?php echo $_html['email']; ?></a></dd>
				<dd class="url">网址：<a href="<?php echo $_html['url']; ?>" target="_black"><?php echo $_html['url']; ?></a></dd>
			</dl>
			<div class="content">
				<div class="user">
					<span><?php echo ($_i+($_page-1)*$_pagesize); ?>楼</span><p><?php echo $_html['username']; ?>|回复于：<?php echo $_html['date']; ?></p>
				</div>
				<h3><i class="ans">re：<?php echo $_html['title']; ?></i><img src="images/icon<?php echo $_html['type']; ?>.png" alt="icon"><?php echo $_html['re']; ?></h3>
				<div class="detail">
					<?php echo ubb($_html['content']); ?>
					<?php //个性签名
						if ($_html['switch']==1) {
							echo '<p class="autograph">个性签名：'.$_html['autograph'].'</p>';
						}
					?>
				</div>
			</div>
		</div>
		<p class="line"></p>

		<?php 
			$_i++;
			}
			_free_result($_result);
			//设置分页类型,1表示数字分页,2表示文本分页
			_paging(1);
		?>
		<?php 
			if (!!$_rows=_fetch_array("SELECT tg_id FROM tg_artical WHERE tg_id='{$_GET['id']}'")) {
				if (isset($_COOKIE['username'])) {
		?>
			<a name="rea"></a>
			<form method="post" action="?action=reartical">
			<input type="hidden" name="reid" value="<?php echo $_html['reid']; ?>">
			<input type="hidden" name="type" value="<?php echo $_html['type']; ?>">
				<dl>
					<dd>回&nbsp;&nbsp;&nbsp;复：<input type="text" name="title" class="text" value="<?php echo $_html['title']; ?>"> (*必填，2-40位)</dd>
					<dd id="q">贴&nbsp;&nbsp;&nbsp;图：<a href="javascript:;">Q图系列[1]</a>&nbsp;<a href="javascript:;">Q图系列[2]</a>&nbsp;<a href="javascript:;">Q图系列[3]</a></dd>
					<dd>
						<?php include ROOT_PATH.'includes/ubb.inc.php'; ?>
						<textarea name="content" rows="9"></textarea>
					</dd>
					<dd>
					<?php if (!empty($_system['code'])) {?>
						验&nbsp;证&nbsp;码&nbsp;：
						<input type="text" name="code" class="text code">
						<img src="code.php" alt="验证码" id="code">
					<?php } ?>
						<input type="submit" class="submit" value="发表">
					</dd>
				</dl>
			</form>
		<?php } } ?>
	</div>
	<?php 
		require ROOT_PATH.'includes/footer.inc.php';
	?>
</body>
</html>