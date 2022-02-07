import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

const store =  new Vuex.Store({
	state: {
		user: {
			name: null,
			email: null,
		},
		csrf: null,
	},

	getters: {
		testLog (state) {
			console.log(state.test);
		},
	},
	mutations: {
		getCsrfToken(state, token) {
			state.csrf = token
		}
	}
});

export default store
