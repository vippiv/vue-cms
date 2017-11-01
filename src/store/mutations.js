export default{
	saveSession : function(state,sid){
		state.sessionId = sid;
	},
	userGroup : function(state,ug){
		state.userGroup = ug;
	},
	ability : function(state,aly){
		state.ability = aly;
	}
}
