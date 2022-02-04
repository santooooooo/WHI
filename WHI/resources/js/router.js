import Vue from "vue";
import VueRouter from "vue-router";
Vue.use(VueRouter);

import Home from "./components/Home.vue";

const routes = [{ path: "/", component: Home }];

const router = new VueRouter({
    mode: "history",
    routes,
});

export default router;
