<template>
  <div class="admin h100">
  	<div class="container-fluid h100">
  		<div class="row h100">
	  		<div class="panel text-left rel container-fluid">
	  			<h1>信息管理系统</h1>
	  			<div class="global-opertor">
	  				<span>您好：<span class='c1'>{{ability | reverseAdmin}}</span> <span class="c2 bold">{{userGroup}}</span></span>
	  				<span class="c1 quit" @click="quit">退出</span>
	  			</div>
	  		</div>
	  		<div class="col-md-1 h100 br1 rel">
	  			<router-link to="infoList" class="btn btn-primary mb15">信息列表</router-link>
	  			<router-link to="inputInfo" class="btn btn-primary mb15" v-if="ability!=3">信息录入</router-link>
	  			<div class="panel-tip" v-if="ability!=3">
	  				<p>管理员</p>
	  				<ul class="panel-tip-item">
	  					<li><router-link to="addAdmin">添加管理员</router-link></li>
	  					<li><router-link to="adminList">管理员管理</router-link></li>
	  				</ul>
	  			</div>
	  		</div>
	  		<div class="col-md-11">
	  			<router-view></router-view>
	  		</div>
  		</div>
  	</div>
  </div>
</template>

<script>
export default {
  name: 'admin',
  data () {
    return {
      sessionId : "",
      userGroup : "",
      ability : ""
    }
  },
  methods : {
		quit : function(){
			var isquit = confirm("确定退出吗？");
			if(isquit){
				this.$cookie.delete("sessionid");
				this.$store.commit("saveSession","");
				this.$router.push({path:"/"});
			}
		}
  },
  created : function(){
		this.$checkLogin();

		this.userGroup = this.$store.state.userGroup;
		
		this.sessionId = this.$store.state.sessionId;
		
		this.ability = this.$store.state.ability;
  }
}
</script>


<style scoped>
.quit{cursor: pointer;}
.panel-tip{border-top:1px solid #C1C1C1;position: fixed; left: 0; bottom: 0px; width: 112px; line-height: 30px;background-color: #f7f9f9;}
.panel-tip > p{margin: 0;}
.panel-tip-item{position: absolute; bottom: 30px;width: 100%;display: none;}
.panel-tip-item > li{line-height: 40px;}
.panel-tip:hover .panel-tip-item{display: block;}
</style>
