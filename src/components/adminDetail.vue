<template>
  <div class="detail">
  	<div class="container-fluid">
			<div class="col-md-12 text-left">
				<div class="form-group">
					<label for="username">登录名：</label>
					<input type="text" name="username" id="username" v-model="username" value="" class="form-control" disabled />
				</div>
				<div class="form-group">
					<label for="passward">密码：</label>
					<input type="text" name="passward" id="passward" v-model="passward" value="" class="form-control" disabled />
				</div>
				<div class="form-group">
					<label for="Jurisdiction">权限：</label>
					<select name="Jurisdiction" id="Jurisdiction" class="form-control" v-model="ability" disabled>
						<option value="1">超级管理员</option>
						<option value="2">普通管理员</option>
						<option value="3">游客</option>
					</select>
				</div>
				<div class="form-group">
					<button class="btn btn-primary mr10" @click="handle">{{opertor}}</button><button class="btn btn-primary" v-bind:class={disabled:disabled} @click="updateData" >提交</button>
				</div>
			</div>
		</div>
  </div>
</template>

<script>
export default {
  name: 'detail',
  data () {
    return {
    	disabled : true,
    	username : "",
    	passward : "",
    	ability : "",
    	opertor : "编辑",
    	id : this.$route.query.id,
    }
  },
  methods : {
    	handle : function(){
    		this.disabled = !this.disabled;
    		!this.disabled ? this.removeAttr() : this.addAttr();
    		this.opertor === "编辑" ? this.opertor = "放弃" : this.opertor = "编辑";
    	},
    	removeAttr : function(){
    		Array.prototype.slice.call(document.querySelectorAll("input"),0).forEach(function(item){
    			item.removeAttribute("disabled");
    		});
    				Array.prototype.slice.call(document.querySelectorAll("select"),0).forEach(function(item){
    			item.removeAttribute("disabled");
    		});
    	},
    	addAttr : function(){
    		Array.prototype.slice.call(document.querySelectorAll("input"),0).forEach(function(item){
    			item.setAttribute("disabled","true");
    		});
    				Array.prototype.slice.call(document.querySelectorAll("select"),0).forEach(function(item){
    			item.setAttribute("disabled","true");
    		});
    	},
    	updateData : function(){
    		if(this.disabled){return;}
				var _this = this;
				var data = {
					username : this.username,
					passward : this.passward,
					ability : this.ability,
				}
				this.$http.post(this.$store.state.dbLocation+"user.php?action=update&id="+this.id,data,{headers : {'Content-Type' : 'application/x-www-form-urlencoded; charset=UTF-8'}
				}).then(function(res){
					if(res.statusText.toLowerCase()=="ok"&&res.status=="200"){
	    			alert("数据更新成功");
	    			_this.$router.push({path:"/adminMain/adminList"});
	    		}
				},function(res){
					_this.tip = res.statusText;
				});
			}
  },
  created : function(){
  	var _this = this;
		this.$http.get(this.$store.state.dbLocation+"user.php?action=view&id="+this.id).then(function(res){
			var data = res.data[0];
			_this.username = data.username;
    	_this.passward = data.passward;
    	_this.ability = data.ability
		},function(res){
			console.log(res);
		});
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

</style>
