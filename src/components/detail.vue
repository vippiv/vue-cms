<template>
  <div class="detail">
  	<div class="container-fluid">
			<div class="col-md-12 text-left">
				<div class="form-group">
					<label for="companyName">公司名称：</label>
					<input type="text" name="companyName" id="companyName" v-model="companyName" value="" class="form-control" disabled />
				</div>
				<div class="form-group">
					<label for="companyName">公司网址：</label>
					<input type="text" name="website" id="website" v-model="website" value="" class="form-control" disabled />
				</div>
				<div class="form-group">
					<label for="companyName">手机：</label>
					<input type="text" name="mobilephone" id="mobilephone" v-model="mobilephone" value="" class="form-control" disabled />
				</div>
				<div class="form-group">
					<label for="companyName">座机：</label>
					<input type="text" name="telephone" id="telephone" v-model="telephone" value="" class="form-control" disabled />
				</div>
				<div class="form-group">
					<label for="companyName">邮箱：</label>
					<input type="text" name="email" id="email" v-model="email" value="" class="form-control" disabled />
				</div>
				<div class="form-group">
					<label for="companyName">QQ：</label>
					<input type="text" name="qq" id="qq" v-model="qq" value="" class="form-control" disabled />
				</div>
				<div class="form-group">
					<label for="companyName">备注：</label>
					<textarea name="note" rows="6" v-model="note" class="form-control" disabled ></textarea>
				</div>
				<div class="form-group">
					<button class="btn btn-primary mr10" @click="handle" v-if="ability!=3">{{opertor}}</button><button class="btn btn-primary" v-bind:class={disabled:disabled} @click="updateData" >提交</button>
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
    	companyName : "苏州书生商友",
    	website : "http://www.baidu.com/",
    	mobilephone : "13402515810",
    	telephone : "0512-65729918",
    	email : "2950252031@qq.com",
    	qq : "2950252031",
    	note : "没有备注",
    	opertor : "编辑",
    	id : this.$route.query.id,
    	ability : ""
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
    		Array.prototype.slice.call(document.querySelectorAll("textarea"),0).forEach(function(item){
    			item.removeAttribute("disabled");
    		});
    	},
    	addAttr : function(){
    		Array.prototype.slice.call(document.querySelectorAll("input"),0).forEach(function(item){
    			item.setAttribute("disabled","true");
    		});
    		Array.prototype.slice.call(document.querySelectorAll("textarea"),0).forEach(function(item){
    			item.setAttribute("disabled","true");
    		});
    	},
    	updateData : function(){
    		if(this.disabled){return;}
				var _this = this;
				var data = {
					companyName : this.companyName,
					website : this.website,
					mobilephone : this.mobilephone,
					telephone : this.telephone,
					email : this.email,
					qq : this.qq,
					note : this.note
				}
				this.$http.post(this.$store.state.dbLocation+"infoList.php?action=update&id="+this.id,data,{headers : {'Content-Type' : 'application/x-www-form-urlencoded; charset=UTF-8'}
				}).then(function(res){
					if(res.statusText.toLowerCase()=="ok"&&res.status=="200"){
	    			alert("数据更新成功");
	    			_this.$router.push({path:"/adminMain/infoList"});
	    		}
				},function(res){
					_this.tip = res.statusText;
				});
			}
  },
  created : function(){
  	var _this = this;
		this.$http.get(this.$store.state.dbLocation+"infoList.php?action=view&id="+this.id).then(function(res){
			var data = res.data[0];
			_this.companyName = data.cname;
    	_this.website = data.website,
    	_this.mobilephone = data.mobilephone,
    	_this.telephone = data.telephone,
    	_this.email = data.email,
    	_this.qq = data.qq,
    	_this.note = data.note
		},function(res){
			_this.data = res.data[0];
		});
		this.ability = this.$store.state.ability;
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
