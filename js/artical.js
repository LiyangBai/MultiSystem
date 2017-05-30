window.onload=function(){
	code();
	var ubb=document.getElementById("ubb");
	var font=document.getElementById("font");
	var color=document.getElementById("color");
	var html=document.getElementsByTagName("html")[0];
	var q=document.getElementById("q");
	var message=document.getElementsByName("message");
	var friend=document.getElementsByName("friend");
	var flower=document.getElementsByName("flower");
	var re=document.getElementsByName("re");
	for (var i = 0; i < re.length; i++) {
		re[i].onclick=function(){
			document.getElementsByTagName("form")[0].title.value=this.title;
		}
	}
	for (var i = 0; i < message.length; i++) {
		message[i].onclick=function(){
			centerWindow('message.php?id='+this.title,'message',300,400);
		}
	}
	for (var i = 0; i < friend.length; i++) {
		friend[i].onclick=function(){
			centerWindow('friend.php?id='+this.title,'friend',300,400);
		}
	}
	for (var i = 0; i < flower.length; i++) {
		flower[i].onclick=function(){
			centerWindow('flower.php?id='+this.title,'flower',300,400);
		}
	}
	if (q!=null) {
		var qa=q.getElementsByTagName("a");
		qa[0].onclick=function(){
			window.open("q.php?num=10&path=qpic/1/","q","width=400,height=400");
		};
		qa[1].onclick=function(){
			window.open("q.php?num=13&path=qpic/2/","q","width=400,height=400");
		};
		qa[2].onclick=function(){
			window.open("q.php?num=6&path=qpic/3/","q","width=400,height=400");
		};
	}
	if (font!=null) {
		html.onmouseup=function(){
			font.style.display = "none";
			color.style.display = "none";
		};
	}
	if (ubb!=null) {
		var ubbimg=ubb.getElementsByTagName("img");
		ubbimg[0].onclick=function(){
			font.style.display = "block";
		};
		ubbimg[2].onclick=function(){
			content("[b][/b]");
		};
		ubbimg[3].onclick=function(){
			content("[i][/i]");
		};
		ubbimg[4].onclick=function(){
			content("[u][/u]");
		};
		ubbimg[5].onclick=function(){
			content("[s][/s]");
		};
		ubbimg[7].onclick=function(){
			color.style.display = "block";
			var t=color.lastChild.firstChild;
			if (t!=null) {
				t.focus();
			}	
		};
		ubbimg[8].onclick=function(){
			var url=prompt("请输入网址：", "http://");
			if (url) {
				if (/^http(s)?:\/\/(\w+\.)?[\w\-\.]+(\.\w+)+$/.test(url)) {
					content("[url]"+url+"[/url]");
				}else{
					alert("网址格式错误！");
				}
			}	
		};
		ubbimg[9].onclick=function(){
			var email=prompt("请输入电子邮件：", "");
			if (email) {
				if (/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/.test(email)) {
					content("[email]"+email+"[/email]");
				}else{
					alert("电子邮件格式错误！");
				}
			}	
		};
		ubbimg[10].onclick=function(){
			var img=prompt("请输入图片地址：", "");
			if (img) {
				content("[img]"+img+"[/img]");
			}	
		};
		ubbimg[11].onclick=function(){
			var flash=prompt("请输入flash：", "http://");
			if (flash) {
				if (/^http(s)?:\/\/(\w+\.)?[\w\-\.]+(\.\w+)+/.test(flash)) {
					content("[flash]"+flash+"[/flash]");
				}else{
					alert("网址格式错误！");
				}
			}	
		};
		ubbimg[12].onclick=function(){
			var movie=prompt("请输入影片地址：", "http://");
			if (movie) {
				if (/^http(s)?:\/\/(\w+\.)?[\w\-\.]+(\.\w+)+/.test(movie)) {
					content("[movie]"+movie+"[/movie]");
				}else{
					alert("网址格式错误！");
				}
			}	
		};
		ubbimg[18].onclick=function(){
			if (fm.content.rows<24) {
				fm.content.rows+=5;
			}
		};
		ubbimg[19].onclick=function(){
			if (fm.content.rows>9) {
				fm.content.rows-=5;
			}
		};
	}
	function content(string){
		fm.content.value+=string;
	}
	if (fm!=undefined) {
		var fm=document.getElementsByTagName("form")[0];
		fm.t.onclick=function(){
			showcolor(this.value);
		}
		fm.onsubmit=function(){
			//标题验证
			if (fm.title.value.length>40) {
				alert('标题不得大于40位！');
				fm.title.focus();
				return false;
			}
			//内容验证
			if (fm.content.value.length<10) {
				alert('内容不得小于10位！');
				fm.content.focus();
				return false;
			}
			//验证码
			if (fm.code.value.length!=4) {
				alert('验证码不能小于4位！');
				fm.code.value="";
				fm.code.focus();
				return false;
			}
			return true;
		};
	}
};
function centerWindow(url,name,height,width){
	var top=(screen.height-height)/2;
	var left=(screen.width-width)/2;
	window.open(url,name,'height='+height+',width='+width+',top='+top+',left='+left);
}
function font(size){
	document.getElementsByTagName("form")[0].content.value+="[size="+size+"][/size]";
}
function showcolor(value){
	document.getElementsByTagName("form")[0].content.value+="[color="+value+"][/color]";
}