window.onload=function(){
	code();
	//客户端登录验证
	var fm=document.getElementsByTagName("form")[0];
	var reg=fm.register;
	fm.onsubmit=function(){
		//用户名验证
		if (fm.username.value.length<2||fm.username.value.length>20) {
			alert('用户名小于2位或大于20位！');
			fm.username.value="";
			fm.username.focus();
			return false;
		}
		if (/[<>\'\"\	\ ]/.test(fm.username.value)) {
			alert('用户名含有非法字符！');
			fm.username.value=""; 
			fm.username.focus();
			return false;
		}
		//密码验证
		if (fm.passward.value.length<6) {
			alert('密码不能小于6位！');
			fm.passward.value="";
			fm.passward.focus();
			return false;
		}
		//验证码
		if (fm.code.value.length!=4) {
			alert('验证码不能小于4位！');
			fm.code.value="";
			fm.code.focus();
			return false;
		}
	};
	reg.onclick=function(){
		location.href="register.php";
	}
};