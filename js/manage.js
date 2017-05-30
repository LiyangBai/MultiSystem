window.onload=function(){
	code();
	//客户端表单验证
	var fm=document.getElementsByTagName("form")[0];
	if (fm==undefined) {
		return false;
	}
	fm.onsubmit=function(){
		//用户名验证
		if (fm.username.value.length<2||fm.username.value.length>20) {
			alert('用户名不能小于2位或大于20位！');
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
		//密码提示
		if (fm.question.value.length<2||fm.question.value.length>20) {
			alert('密码提示不能小于2位或大于20位！');
			fm.question.value="";
			fm.question.focus();
			return false;
		}
		//密码回答
		if (fm.answer.value.length<2||fm.answer.value.length>20) {
			alert('密码回答不能小于2位或大于20位！');
			fm.answer.value="";
			fm.answer.focus();
			return false;
		}
		if (fm.question.value==fm.answer.value) {
			alert('密码提示不能与密码回答一致！');
			fm.answer.focus();
			return false;
		}
		//邮箱验证
		if (!/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/.test(fm.email.value)) {
			alert('邮箱格式错误！');
			fm.email.focus();
			return false;
		}
		//qq验证
		if (fm.qq.value!='') {
			if (!/^[1-9]{1}[\d]{4,9}$/.test(fm.qq.value)) {
				alert('QQ长度错误！');
				fm.qq.value="";
				fm.qq.focus();
				return false;
			}
		}
		//网址验证
		if (fm.url.value!='') {
			if (!/^http(s)?:\/\/(\w+\.)?[\w\-\.]+(\.\w+)+$/.test(fm.url.value)) {
				alert('网址格式错误！');
				fm.url.value="";
				fm.url.focus();
				return false;
			}
		}
		//验证码
		if (fm.code.value.length!=4) {
			alert('验证码不能小于4位！');
			fm.code.value="";
			fm.code.focus();
			return false;
		}
		return true;
	}
};