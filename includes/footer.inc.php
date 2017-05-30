<?php 
/*
*Author:白小七
*Date:2017-5-8
*/
//防止恶意调用
if (!defined('IN_TG')) {
	exit('ILLEGAL ACCESS!');
}
mysql_close();
?>
<div id="footer">
	<p>执行耗时：<?php echo round((_runtime()-$_start_time),4);?>秒</p>
	<p>版权所有 翻版必究</p>
	<p><span>白小七的世界</span>你们不懂</p>
</div>