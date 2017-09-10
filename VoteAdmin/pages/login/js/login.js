$(document).ready(function(){
	$("#submit").click(function(){
		var username = $("[name='username']").val();
		var password = $("[name='password']").val();
		if (username.length == 0 || password.length == 0) {
			$(".hint").html("用户名和密码不能为空");
			return;
		}
		if (containSpecial(username) || containSpecial(password)){
			$(".hint").html("用户名或密码中存在特殊字符");
			return;
		} 
		$.ajax({
			url:  'loginCheck.php',
			type: 'post',
			data: {username:username, password:password},
			success:function(data){
				var jsonD = JSON.parse(data);
				if (jsonD.code == 0) {
					$('#button').html('<button id="submit" type="submit" class="btn btn-primary btn-block btn-flat" style="border-radius: 3px;">登录</button>');
					$('#submit').click();
				}else{
					$(".hint").html("用户名或密码错误");
				}
			},
			error:function(data){
				$(".hint").html("发生了错误，请刷新重试");
			}
		})
	})
  	
});

/*特殊字符检测*/
function containSpecial( s ){      
    var containSpecial = RegExp(/[(\#)(\^)(\()(\))(\[)(\])(\{)(\})(\|)(\\)(\;)(\:)(\')(\")(\/)(\)]+/);     
    return ( containSpecial.test(s) );      
}