window.onload=function(){
	var ret=document.getElementById("return");
	var del=document.getElementById("delete");
	ret.onclick=function(){
		history.back();
	};
	del.onclick=function(){
		if (confirm("确定删除该短信？")) {
			location.href="?action=delete&id="+this.name;
		}
	};
};