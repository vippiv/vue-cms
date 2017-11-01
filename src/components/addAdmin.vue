<template>
  <div class="addadmin h100">
  	<div class="container-fluid">
			<div class="col-md-12 text-left">
				<div class="form-group">
					<label for="username">用户名：</label>
					<input type="text" name="username" id="username" v-model="username" ref="username" value="" class="form-control"/>
				</div>
				<div class="form-group">
					<label for="passward">密码：</label>
					<input type="text" name="passward" id="passward" v-model="passward" ref="passward" value="" class="form-control"/>
				</div>
				<div class="form-group">
					<label for="Jurisdiction">权限：</label>
					<select name="Jurisdiction" id="Jurisdiction" class="form-control" v-model="ability">
						<option value="1">超级管理员</option>
						<option value="2">普通管理员</option>
						<option value="3">游客</option>
					</select>
				</div>
				<div class="form-group">
					<button class="btn btn-primary" @click="addAdmin">提交</button>
				</div>
			</div>
		</div>
  </div>
</template>

<script>
export default {
  name: 'addadmin',
  data () {
    return {
    	username : "",
    	passward : "",
    	ability : 2
    }
  },
  methods : {
    addAdmin : function(){
    	if(!this.check()){
				return;
			}
    	var _this = this;
				if(!(this.username && this.passward)){
					alert('请输入用户名/密码');
					return;
				}
				this.$http.post(this.$store.state.dbLocation+"user.php?action=add",{
					username : this.username,
					passward : this.passward,
					ability : this.ability
				},{headers : {'Content-Type' : 'application/x-www-form-urlencoded; charset=UTF-8'}
				}).then(function(res){
					console.log(res);
					if(res.status==200 && res.statusText.toLowerCase() == "ok"){
						alert('添加成功');
					}
				},function(res){
					alert("添加失败");
				});
				
				
    		this.emptyInput();
    },
    emptyInput : function(){
			this.username = "";
			this.passward = "";
		},
		check :function(){
			if(this.isEmpty()){
				alert("用户名/密码不能为空");
				return false;
			}
			if(this.checkRule(this.$refs.username.value)){
				alert("用户名必须由数字，字母，下划线构成");
				return false;
			}
			if(this.checkRule(this.$refs.passward.value)){
				alert("密码必须由数字，字母，下划线构成");
				return false;
			}
			return true;
    },
    isEmpty : function(){
    	return this.$refs.username.value=="" && this.$refs.passward.value=="";
    },
    checkRule : function(value){
    	var reg = /^[0-9a-zA-Z_]{1,}$/;
    	if(value!==""){
    		return !reg.test(value);
    	}
    	return false;
    },
  },
  created : function(){
	  
  }
}
</script>


<style scoped>

</style>
