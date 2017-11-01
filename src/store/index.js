import Vue from "vue"
import Vuex from "vuex"
import mutations from "./mutations"
import actions from "./actions"

Vue.use(Vuex)

const state = {
	sessionId : "",
	dbLocation : "http://localhost/cms2/database/",
	userGroup : "",
	ability : ""
}

export default new Vuex.Store({
	state,
	mutations,
	actions
})
