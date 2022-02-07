import Vue from "vue";
import VueRouter from "vue-router";
Vue.use(VueRouter);

import Home from "./components/EveryOne/Home.vue";
import Signup from "./components/EveryOne/Signup.vue";

const routes = [{ path: "/", component: Home },{ path: "/signup", component: Signup }];

const router = new VueRouter({
//    mode: "history",
    routes,
});

export default router;
