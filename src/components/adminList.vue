<template>
  <div class="admin-list h100">
  	<table class="table table-striped table-bordered text-left">
			<thead>
  				<tr>
  					<th>编号</th>
  					<th>登录名</th>
  					<th>权限</th>
  					<th>创建时间</th>
  					<th class='text-center'>操作</th>
  				</tr>
			</thead>
			<tbody>
				<tr v-for="(item,index) in adminList">
					<td class="text-center">{{item.id}}</td>
					<td>{{item.username}}</td>
					<td>{{item.ability | reverseAdmin}}</td>
					<td>{{item.createAt}}</td>
					<td><a href="javascript:;" @click="del(item.id,index)">删除</a> | <router-link :to="{ name: 'adminDetail', query: { id: item.id }}">编辑</router-link></td>
				</tr>
			</tbody>
		</table>
  </div>
</template>

<script>
export default {
  name: 'adminList',
  data () {
    return {
    	adminList : null,
    }
  },
  methods : {
    del : function(id,index){
    	if(!this.isDel()){
				return;
			};
    	//判断是否为超级管理员，如果是则不能删除
    	var isSuperAdmin = this.adminList[index].ability;
    	if(isSuperAdmin==1){
    		alert("您不能删除超级管理员");
    		return;
    	}
			var _this = this;
			this.$http.delete(this.$store.state.dbLocation+"user.php?action=del&id="+id).then(function(res){
				if(res.statusText.toLowerCase()=="ok"&&res.status=="200"){
					delete _this.adminList[index];
					_this.getData();
    			alert("删除成功");
    		}
			},function(res){
				
			});
		},
		getData : function(){
			var _this = this;
		  this.$http.get(this.$store.state.dbLocation+"user.php?action=list",{headers : {'Content-Type' : 'application/x-www-form-urlencoded; charset=UTF-8'}
			}).then(function(res){
				if(res.status==200 && res.statusText.toLowerCase() == "ok"){
					_this.adminList = res.data;
				}
			},function(res){
				alert("获取失败，重新获取");
			});
		}
  },
  created : function(){
  	this.getData();
  }
}
</script>


<style scoped>

</style>
