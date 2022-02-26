import Vue from "vue";
import Vuex from "vuex";
import createPersistedState from "vuex-persistedstate";
import Cookies from "js-cookie";

Vue.use(Vuex);

const store = new Vuex.Store({
    plugins: [
        createPersistedState({
            storage: {
                getItem: (key) => Cookies.get(key),
                setItem: (key, value) =>
                    Cookies.set(key, value, { expires: 1, secure: false }),
                removeItem: (key) => Cookies.remove(key),
            },
        }),
    ],
    state: {
        user: {
            id: null,
            name: null,
            icon: null,
        },
    },

    getters: {
        testLog(state) {
            console.log(state.test);
        },
    },
    mutations: {
        setUserInfo(state, userInfo) {
            state.user.id = userInfo["id"];
            state.user.name = userInfo["name"];
        },
        setUserIcon(state, userIcon) {
            state.user.icon = userIcon;
        },
        resetUserInfo(state) {
            state.user.id = null;
            state.user.name = null;
            state.user.icon = null;
        },
    },
});

export default store;
