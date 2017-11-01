<template>
  <div class="login">
  	<div class="login-container">
	    <div class="container-fluid">
	    	<div class="row">
	    		<div class="col-md-3"><label for="username">用户名：</label></div>
	    		<div class="col-md-9 form-group"><input type="text" name="username" id="username" class="form-control" v-model="username" placeholder="用户名"/></div>
	    		<div class="col-md-3"><label for="username">密码：</label></div>
	    		<div class="col-md-9 form-group"><input type="password" name="password" id="password" class="form-control" v-model="passward" placeholder="密码"/></div>
	    		<div class="com-md-12"><button class="btn btn-primary" @click="check">登录</button><button class="btn btn-primary disabled">注册</button></div>
	    	</div>
	    </div>
   </div>
  </div>
</template>

<script>
export default {
  name: 'login',
  data () {
    return {
      username : "",
      passward : "",
    }
  },
  methods : {
    	check : function(){
    		var _this = this;
				if(!(this.username && this.passward)){
					alert('请正确输入用户名/密码');
					return;
				}
				this.$http.post(this.$store.state.dbLocation+"login.php",{
					username : this.username,
					passward : this.passward
				},{headers : {'Content-Type' : 'application/x-www-form-urlencoded; charset=UTF-8'}
				}).then(function(res){
					//登录成功返回200 OK
					//登录失败返回400 Bad Request
					if(res.status==200 && res.statusText.toLowerCase() == "ok"){
						//这里要将sessionid存在cookie中，做登录状态验证
						var sessionid = res.data["sessionid"];
						var userGroup = res.data["username"];
						var ability = res.data["ability"];
						_this.$cookie.set("sessionid",sessionid,1);
						//sessionid和userGroup都要保存起来
						_this.saveSession(sessionid);
						_this.saveUserGroup(userGroup);
						_this.saveAbility(ability);

						_this.$router.push({path:"adminMain/infoList"});
					}
				},function(res){
					alert("用户名/密码错误");
				});
				
				
    		this.emptyInput();
    	},
    	emptyInput : function(){
    		this.username = "";
    		this.passward = "";
    	},
    	saveSession : function(sid){
    		this.$store.commit("saveSession",sid);
    	},
    	saveUserGroup : function(ug){
    		this.$store.commit("userGroup",ug);
    	},
    	saveAbility : function(aly){
    		this.$store.commit("ability",aly);
    	}
  },
  created : function(){
		this.$checkLogin();
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
h1, h2 {
  font-weight: normal;
}

ul {
  list-style-type: none;
  padding: 0;
}

li {
  display: inline-block;
  margin: 0 10px;
}

a {
  color: #42b983;
}
.login{height:100%;background: url("/static/images/login_bg.jpg") no-repeat; background-size: cover;color: white;}
.login-container{width: 500px; margin: 0 auto;position: absolute; left:50%;top: 50%;margin-left: -250px;margin-top: -66px;}
.login-container button:first-child{margin-right: 10px;}
</style>
