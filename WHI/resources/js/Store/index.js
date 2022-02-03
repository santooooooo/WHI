import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

const store =  new Vuex.Store({
	state: {
		test: 'jamboo'
	},

	getters: {
		testLog (state) {
			console.log(state.test);
		},
	},
});

export default store
