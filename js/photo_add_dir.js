window.onload=function(){
	var fm=document.getElementsByTagName("form")[0];
	var pass=document.getElementById("passward");
	fm[1].onclick=function(){
		pass.style.display="none";
	};
	fm[2].onclick=function(){
		pass.style.display="block";
	};
	fm.onsubmit=function(){
		if (fm.name.value.length<2||fm.name.value.length>20) {
			alert('相册名不能小于2位或大于20位！');
			fm.name.value="";
			fm.name.focus();
			return false;
		}
		if (fm[2].checked) {
			if (fm.passward.value.length<6) {
				alert('密码不能小于6位！');
				fm.passward.value="";
				fm.passward.focus();
				return false;
			}
		}
		return true;
	};
};