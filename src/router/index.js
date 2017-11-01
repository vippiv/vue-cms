import Vue from 'vue'
import Router from 'vue-router'
import Login from '@/components/Login'
import adminMain from '@/components/adminMain'
import infoList from '@/components/infoList'
import inputInfo from '@/components/inputInfo'
import detail from '@/components/detail'
import adminDetail from '@/components/adminDetail'
import addAdmin from '@/components/addAdmin'
import adminList from '@/components/adminList'

Vue.use(Router)

export default new Router({
	//这里的页面跳转不会刷新页面，跟传统网页不一样，因为在路由之间跳转不会导致store中的信息丢失
  routes: [
    {
      path: '/',
      name: 'Login',
      component: Login
    },
     {
      path: '/adminMain',
      name: 'adminMain',
      component: adminMain,
      children : [
      	{
      		path : "infoList",
      		component : infoList,
      	},
      	{
      		path : "inputInfo",
      		component : inputInfo
      	},
      	{
      		name : "detail",
      		path : "detail",
      		component : detail
      	},
      	{
      		name : "adminDetail",
      		path : "adminDetail",
      		component : adminDetail
      	},
      	{
      		name : "addAdmin",
      		path : "addAdmin",
      		component : addAdmin
      	},
      	{
      		name : "adminList",
      		path : "adminList",
      		component : adminList
      	}
      ]
    }
  ]
})
