import Vue from "vue";
import VueRouter from "vue-router";
Vue.use(VueRouter);

import Home from "./components/EveryOne/Home.vue";
import Signup from "./components/EveryOne/Signup.vue";
import Login from "./components/EveryOne/Login.vue";
import MyPage from "./components/User/MyPage.vue";
import EditBlog from "./components/User/EditBlog.vue";
import Blog from "./components/EveryOne/Blog.vue";
import PRpage from "./components/EveryOne/PRpage.vue";
import ResetPassword from "./components/EveryOne/ResetPassword.vue";
import ForgetPassword from "./components/EveryOne/ForgetPassword.vue";

const routes = [
    { path: "/", component: Home },
    { path: "/signup", component: Signup },
    { path: "/login", component: Login },
    { path: "/mypage", component: MyPage },
    { path: "/sections/:sectionId/edit-blog/:blogId", component: EditBlog },
    { path: "/blogs/:id", component: Blog },
    { path: "/PRpage/:id", component: PRpage },
    { path: "/reset-password", component: ResetPassword },
    { path: "/forget-password", component: ForgetPassword },
];

const router = new VueRouter({
    //    mode: "history",
    routes,
});

export default router;
