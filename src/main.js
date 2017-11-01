//本站权限分两种，1为超级管理员，2为普通管理员
// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import App from './App'
import router from './router'
import axios from 'axios'
import cookie from "vue-cookie"

import store from "./store"

Vue.prototype.$http = axios
Vue.use(cookie)

Vue.prototype.$checkLogin = function(){
	var _this = this;
	//sessionid要在cookie中存一份，这里要从cookie中获取才能做到登录状态判断
	var sessionid = this.$cookie.get("sessionid") || this.$store.state.sessionId;
	if(sessionid){
		this.$store.commit("saveSession",sessionid);
		this.$http.post(this.$store.state.dbLocation+"checklogin.php",{
			sessionId : sessionid,
		},{headers : {'Content-Type' : 'application/x-www-form-urlencoded; charset=UTF-8'}
		}).then(function(res){
			if(res.status==200 && res.statusText.toLowerCase() == "ok"){
				_this.$store.commit("userGroup",res.data["username"]);
				_this.$store.commit("ability",res.data["ability"]);
				_this.$router.push({path:"/adminMain/infoList"});
			}else{
				_this.$router.push({path:"/"});
			}
		},function(res){
			_this.$router.push({path:"/"});
		});
	}else{
		this.$router.push({path:"/"});
	}
}
Vue.prototype.isDel = function(){
	return confirm("确定删除吗");
}
Vue.filter("reverseAdmin",function(item){
	if(item==1){
		return "超级管理员";
	}else if(item==2){
		return "普通管理员";
	}else{
		return "游客";
	}
});

Vue.config.productionTip = false
import "bootstrap/dist/css/bootstrap.min.css"
import "../static/css/style.css"
/* eslint-disable no-new */

router.beforeEach(function(to,come,next){
	next();
});


new Vue({
  el: '#app',
  router,
  store,
  template: '<App/>',
  components: { App }
})
