<?php
/*
*Author:白小七
*Date:2017-5-8
*/
session_start();
//定义常量，授权调用includes里面的文件
define('IN_TG',true);
//定义常量，用来指定本页的内容
define('SCRIPT','member_modify');
//引入公共文件
require dirname(__FILE__).'/includes/common.inc.php';
//修改资料
if ($_GET['action']=='modify') {
	//防止恶意注册
	_check_code($_POST['code'],$_SESSION['code']);
	if (!!$_rows=_fetch_array("SELECT tg_uniqid FROM tg_user WHERE tg_username='{$_COOKIE['username']}' LIMIT 1")) {
		//防止cookie伪造,比对数据库唯一标识符与cookie中存的一致
		_uniqid($_rows['tg_uniqid'],$_COOKIE['uniqid']);
		//引入验证文件
		include ROOT_PATH.'includes/check.func.php';
		$_clean=array();
		$_clean['passward']=_check_modify_passward($_POST['passward']);
		$_clean['sex']=_check_sex($_POST['sex']);
		$_clean['face']=_check_face($_POST['face']);
		$_clean['email']=_check_email($_POST['email']);
		$_clean['qq']=_check_qq($_POST['qq']);
		$_clean['url']=_check_url($_POST['url']);
		$_clean['switch']=$_POST['switch'];
		$_clean['autograph']=_check_autograph($_POST['autograph']);
		//修改数据库资料
		if (empty($_clean['passward'])) {
			_query("UPDATE tg_user SET 
				tg_sex='{$_clean['sex']}',tg_face='{$_clean['face']}',
				tg_email='{$_clean['email']}',tg_qq='{$_clean['qq']}',
				tg_url='{$_clean['url']}',tg_switch='{$_clean['switch']}',
				tg_autograph='{$_clean['autograph']}' 
				WHERE tg_username='{$_COOKIE['username']}'");
		}else{
			_query("UPDATE tg_user SET 
				tg_passward='{$_clean['passward']}',tg_sex='{$_clean['sex']}',
				tg_face='{$_clean['face']}',tg_email='{$_clean['email']}',
				tg_qq='{$_clean['qq']}',tg_url='{$_clean['url']}',
				tg_switch='{$_clean['switch']}',tg_autograph='{$_clean['autograph']}'
				WHERE tg_username='{$_COOKIE['username']}'");
		}
		//判断是否修改成功
		if (_affected_rows()==1) {
			_close();
			_location('Modify success!','member.php');
		}else{
			_close();
			_location('Modify fail!','member_modify.php');	
		}
	}
}
//判断是否正常登陆
if (isset($_COOKIE['username'])) {
	$_rows=_fetch_array("SELECT tg_username,tg_sex,tg_face,tg_email,tg_url,tg_qq,tg_switch,tg_autograph FROM tg_user WHERE tg_username='{$_COOKIE['username']}'");
	if ($_rows) {
		$_html=array();
		$_html['username']=$_rows['tg_username'];
		$_html['sex']=$_rows['tg_sex'];
		$_html['face']=$_rows['tg_face'];
		$_html['email']=$_rows['tg_email'];
		$_html['url']=$_rows['tg_url'];
		$_html['qq']=$_rows['tg_qq'];
		$_html['switch']=$_rows['tg_switch'];
		$_html['autograph']=$_rows['tg_autograph'];
		$_html=_html($_html);
		//性别选择
		if ($_html['sex']=='男') {
			$_html['resex']='<input type="radio" name="sex" value="男" checked="checked"/>男<input type="radio" name="sex" value="女"/>女';
		}else if ($_html['sex']=='女') {
			$_html['resex']='<input type="radio" name="sex" value="男"/>男<input type="radio" name="sex" value="女" checked="checked"/>女';
		}
		//头像选择
		$_html['reface']='<select name="face">';
		foreach (range(1,9) as $_num){
			if ($_html['face']=='face/m0'.$_num.'.jpg') {
				$_html['reface'].='<option value="face/m0'.$_num.'.jpg" selected="selected">face/m0'.$_num.'.jpg</option>';
			}else{
				$_html['reface'].='<option value="face/m0'.$_num.'.jpg">face/m0'.$_num.'.jpg</option>';
			}
		}
		foreach (range(10,30) as $_num){
			if ($_html['face']=='face/m'.$_num.'.jpg') {
				$_html['reface'].='<option value="face/m'.$_num.'.jpg" selected="selected">face/m'.$_num.'.jpg</option>';
			}else{
				$_html['reface'].='<option value="face/m'.$_num.'.jpg">face/m'.$_num.'.jpg</option>';
			}
		}
		$_html['reface'].='</select>';
		//个性签名启用判断
		if ($_html['switch']==1) {
			$_html['html_switch']='<input type="radio" name="switch" value="1" checked="checked">启用<input type="radio" name="switch" value="0">禁用';
		}else{
			$_html['html_switch']='<input type="radio" name="switch" value="1">启用<input type="radio" name="switch" value="0" checked="checked">禁用';
		}
	}else{
		_alert_back("Username does not exist");
	}
}else{
	_alert_back("ILLEGAL ACCSEE!");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php require ROOT_PATH.'includes/title.inc.php' ?>
	<script type="text/javascript" src="js/code.js"></script>
	<script type="text/javascript" src="js/member_modify.js"></script>
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
			<h2>会员管理中心</h2>
			<form method="post" action="?action=modify">
				<dl>
					<dd>用&nbsp;户&nbsp;名&nbsp;：<?php echo $_html['username'];?></dd>
					<dd>密&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;码：<input type="passward" class="text" name="passward"> (*留空则不修改)</dd>
					<dd>性&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;别：<?php echo $_html['resex'];?></dd>
					<dd>头&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;像：<?php echo $_html['reface'];?></dd>
					<dd>电子邮件：<input type="text" class="text" name="email" value="<?php echo $_html['email'];?>"></dd>
					<dd>主&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;页：<input type="text" class="text" name="url" value="<?php echo $_html['url'];?>"></dd>
					<dd>Q&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Q：<input type="text" class="text" name="qq" value="<?php echo $_html['qq'];?>"></dd>	
					<dd>个性签名：<?php echo $_html['html_switch']; ?><p><textarea name="autograph"><?php echo $_html['autograph']; ?></textarea></p></dd>
					<dd>验&nbsp;证&nbsp;码&nbsp;：<input type="text" name="code" class="text code"><img src="code.php" alt="验证码" id="code"><input type="submit" class="submit" name="submit" value="修改资料"></dd>
				</dl>
			</form>
		</div>
	</div>
	<?php 
		require ROOT_PATH.'includes/footer.inc.php';
	?>
</body>
</html>