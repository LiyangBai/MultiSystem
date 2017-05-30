<?php
/*
*Author:白小七
*Date:2017-5-8
*/
//防止恶意调用
if (!defined('IN_TG')) {
	exit('ILLEGAL ACCESS!');
}

?>
<div id="member_sidebar">
	<h2>中心导航</h2>
	<dl>
		<dt>账户管理</dt>
		<dd><a href="member.php">个人信息</a></dd>
		<dd><a href="member_modify.php">修改资料</a></dd>
	</dl>
	<dl>
		<dt>其他管理</dt>
		<dd><a href="member_message_outbox.php">发&nbsp;件&nbsp;箱</a></dd>
		<dd><a href="member_message_inbox.php">收&nbsp;件&nbsp;箱</a></dd>
		<dd><a href="member_friend.php">好友设置</a></dd>
		<dd><a href="member_flower.php">查看花朵</a></dd>
		<dd><a href="photo.php">个人相册</a></dd>
	</dl>	
</div>