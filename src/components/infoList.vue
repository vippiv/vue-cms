<template>
  <div class="info-list h100">
		<div class="filter form-inline text-left form-group">
			<label for="filter">筛选：</label><input type="text" id="filter" class="form-control" v-model="searchString"/>
		</div>
		<table class="table table-striped table-bordered text-left">
			<thead>
  				<tr>
  					<th>编号</th>
  					<th>公司名称</th>
  					<th>公司网址</th>
  					<th>手机</th>
  					<th>座机</th>
  					<th>邮箱</th>
  					<th>QQ</th>
  					<th>备注</th>
  					<th class='text-center'>操作</th>
  				</tr>
			</thead>
			<tbody>
				<tr v-for="(item,index) in newCustomer">
					<td class="text-center">{{item.id}}</td>
					<td>{{item.cname}}</td>
					<td>{{item.website}}</td>
					<td>{{item.mobilephone}}</td>
					<td>{{item.telephone}}</td>
					<td>{{item.email}}</td>
					<td>{{item.qq}}</td>
					<td v-bind:title="item.note">{{item.note | cutNote}}</td>
					<td><a href="javascript:;" @click="del(item.id,index)" v-if="ability!=3">删除</a> <span v-if="ability!=3">|</span> <router-link :to="{ name: 'detail', query: { id: item.id }}">详细/编辑</router-link></td>
				</tr>
			</tbody>
		</table>
		<div class="load" :class={disabled:disabled} @click="load($event)">{{loadTips}}</div>
  </div>
</template>

<script>
export default {
  name: 'infoList',
  data () {
    return {
    	customer : [],
    	searchString : "",
    	editable : false,
    	ability : "",
    	page : 1,
    	size : 4,
    	disabled : false,
    	loadTips : "加载更多....."
    }
  },
  methods : {
		filter : function(){
			var ret = {};
			for(var item in this.customer){
				if(this.customer[item].cname.indexOf(this.searchString) !== -1 || 							this.customer[item].website.indexOf(this.searchString) !== -1 || 							this.customer[item].mobilephone.indexOf(this.searchString) !== -1 || 							this.customer[item].telephone.indexOf(this.searchString) !== -1 || 							this.customer[item].email.indexOf(this.searchString) !== -1 || 							this.customer[item].qq.indexOf(this.searchString) !== -1 || 							this.customer[item].note.indexOf(this.searchString) !== -1){
					ret[item] = this.customer[item];
				}
			}
			return ret;
		},
		del : function(id,index){
			if(!this.isDel()){
				return;
			};
			var _this = this;
			this.$http.delete(this.$store.state.dbLocation+"infoList.php?action=del&id="+id).then(function(res){
				if(res.statusText.toLowerCase()=="ok"&&res.status=="200"){
					_this.getData();
    			alert("删除成功");
    		}
			},function(res){
				alert("failed");
				_this.tip = res.statusText;
			});
		},
		getData : function(){
			var _this = this;
			this.$http.get(this.$store.state.dbLocation+"infoList.php?action=list&page="+this.page+"&size="+this.size).then(function(res){
				var tempData = res.data;
				_this.customer = _this.customer.concat(tempData);
				//根据返回结果是否为空来判断是否还存在数据
				if(res.data.length>0){
					_this.page++;
				}else{
					_this.disabled = true;
					_this.loadTips = "已无数据"
					alert(_this.loadTips);
					_this.$off("load");
				}
			},function(res){
				_this.customer = res;
			});	
			
			this.ability = this.$store.state.ability;
		},
		load : function(event){
			this.getData();
		}
  },
  filters : {
		cutNote : function(value){
			return value.replace(/\<\w+\>/g,"").substr(0,20);
		}
  },
  created : function(){
  	this.getData();
	},
	computed : {
		newCustomer : function(){
			var that = this;
			return this.filter();
		}
	}
}
</script>


<style scoped>
table{table-layout: fixed;}
table td{word-wrap: break-word;}
table th:nth-child(1){width: 4%;}
table th:nth-child(2){width: 15%;}
table th:nth-child(3){width: 13%;}
table th:nth-child(7){width: 9%;}
table th:nth-child(9){width: 11%;}
.load{border:1px solid #DDD;line-height: 40px;text-align: center;cursor: pointer;}
.disabled{background: #EEE; cursor: not-allowed;}
</style>
