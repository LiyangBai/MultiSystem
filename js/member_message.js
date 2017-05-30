window.onload=function(){
	var fm=document.getElementsByTagName("form")[0];
	var all=document.getElementById("all");
	if (all==null) {
		return false;
	}
	all.onclick=function(){
		for (var i = 0; i < fm.elements.length; i++) {
			if (fm.elements[i].name!='chekall') {
				fm.elements[i].checked=fm.chekall.checked;
			}
		}
	};
	fm.onsubmit=function(){
		if (confirm("确定删除？")) {
			return true;
		}
		return false;
	};
};