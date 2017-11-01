<template>
  <div class="input-info h100">
		<div class="container-fluid">
			<div class="col-md-12 text-left">
				<div class="form-group">
					<label for="companyName">公司名称：</label>
					<input type="text" name="companyName" id="companyName" v-model="companyName" ref="companyName" value="" class="form-control"/>
				</div>
				<div class="form-group">
					<label for="website">公司网址：</label>
					<input type="text" name="website" id="website" v-model="website" ref="website" value="" class="form-control"/>
				</div>
				<div class="form-group">
					<label for="mobilephone">手机：</label>
					<input type="text" name="mobilephone" id="mobilephone" v-model="mobilephone" ref="mobilephone" value="" class="form-control"/>
				</div>
				<div class="form-group">
					<label for="telephone">座机：</label>
					<input type="text" name="telephone" id="telephone" v-model="telephone" ref="telephone" value="" class="form-control"/>
				</div>
				<div class="form-group">
					<label for="email">邮箱：</label>
					<input type="text" name="email" id="email" v-model="email" ref="email" value="" class="form-control"/>
				</div>
				<div class="form-group">
					<label for="qq">QQ：</label>
					<input type="text" name="qq" id="qq" v-model="qq" ref="qq" value="" class="form-control"/>
				</div>
				<div class="form-group">
					<label for="note">备注：</label>
					<textarea name="note" rows="6" v-model="note" class="form-control"></textarea>
				</div>
				<div class="form-group">
					<button class="btn btn-primary" @click="insertData">提交</button>
				</div>
			</div>
		</div>
  </div>
</template>

<script>
export default {
  name: 'inputInfo',
  data () {
    return {
      companyName : "",
      website : "",
      mobilephone : "",
      telephone : "",
      email : "",
      qq : "",
      note : "",
    }
  },
  methods : {
    	insertData : function(){
    		if(!this.check()){
    			return;
    		}
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
				this.$http.post(this.$store.state.dbLocation+"infoList.php?action=add",data,{headers : {'Content-Type' : 'application/x-www-form-urlencoded; charset=UTF-8'}
				}).then(function(res){
					if(res.statusText.toLowerCase()=="ok"&&res.status=="200"){
						_this.companyName = "";
	    			_this.website = "";
	    			_this.mobilephone = "";
	    			_this.telephone = "";
	    			_this.email = "";
	    			_this.qq = "";
	    			_this.note = "";
	    			alert("数据添加成功");
	    		}
				},function(res){
					_this.tip = res.statusText;
				});
    },
    check :function(){
			if(this.isEmpty()){
				alert("公司名称不能为空");
				return false;
			}
			if(this.checkMobile()){
				alert("手机号码不正确");
				return false;
			}
			if(this.checkTelephone()){
				alert("座机号码不正确");
				return false;
			}
			if(this.checkEmail()){
				alert("邮箱地址不正确");
				return false;
			}
			if(this.checkQQ()){
				alert("QQ号码不正确");
				return false;
			}
			return true;
    },
    isEmpty : function(){
    	return this.$refs.companyName.value=="";
    },
    checkWebSite : function(){
    	var value = this.$refs.website.value; 
			var re = "^((https|http|ftp|rtsp|mms)?://)"  
        + "?(([0-9a-z_!~*'().&=+$%-]+: )?[0-9a-z_!~*'().&=+$%-]+@)?" //ftp的user@   
        + "(([0-9]{1,3}\.){3}[0-9]{1,3}" // IP形式的URL- 199.194.52.184   
        + "|" // 允许IP和DOMAIN（域名）   
        + "([0-9a-z_!~*'()-]+\.)*" // 域名- www.   
        + "([0-9a-z][0-9a-z-]{0,61})?[0-9a-z]\." // 二级域名   
        + "[a-z]{2,6})" // first level domain- .com or .museum   
        + "(:[0-9]{1,4})?" // 端口- :80 <br>  
        + "((/?)|" // a slash isn't required if there is no file name   
        + "(/[0-9a-z_!~*'().;?:@&=+$,%#-]+)+/?)$";  
        var reg = new RegExp(re);  
    	if(value!==""){
    		return reg.test(value);
    	}
    	return false;
    },
    checkMobile : function(){
    	var value = this.$refs.mobilephone.value;
    	var reg = /^1[3|4|5|8][0-9]\d{4,8}$/;
    	if(value!==""){
    		return !reg.test(value);
    	}
    	return false;
    },
    checkTelephone : function(){
    	var value = this.$refs.telephone.value;
    	var reg = /^0\d{2,3}-\d{7,8}(-\d{1,6})?$/;//这个检测有问题，不存在横杠的将通不过
    	if(value!==""){
    		return !reg.test(value);
    	}
    	return false;
    },
    checkEmail : function(){
    	var value = this.$refs.email.value;
    	var reg = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+/;
    	if(value!==""){
    		return !reg.test(value);
    	}
    	return false;
    },
    checkQQ : function(){
    	var value = this.$refs.qq.value;
    	var reg = /^[0-9]*$/;
    	if(value!==""){
    		return !reg.test(value);
    	}
    	return false;
    }
  },
  watch: {
		
  }
}
</script>


<style scoped>

</style>
