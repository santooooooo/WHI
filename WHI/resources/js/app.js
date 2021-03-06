/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require("./bootstrap");
//window.Vue = require('vue').default;
import Vue from "vue";
import Vuetify from "vuetify";
import store from "./Store/index";
import router from "./router";
import App from "./components/App.vue";
import "vuetify/dist/vuetify.min.css";
// ブログ用ツールのプラグイン
import VueMarkdownEditor from "@kangc/v-md-editor";
import "@kangc/v-md-editor/lib/style/base-editor.css";
import vuepressTheme from "@kangc/v-md-editor/lib/theme/vuepress.js";
import "@kangc/v-md-editor/lib/theme/style/vuepress.css";
import Prism from "prismjs";
import "prismjs/components/prism-json";
import enUS from "@kangc/v-md-editor/lib/lang/en-US";

import VMdPreview from "@kangc/v-md-editor/lib/preview";
import "@kangc/v-md-editor/lib/style/preview.css";
//import githubTheme from '@kangc/v-md-editor/lib/theme/github.js';
import "@kangc/v-md-editor/lib/theme/style/github.css";
//import hljs from 'highlight.js';

Vue.use(Vuetify);

VueMarkdownEditor.use(vuepressTheme, {
    Prism,
});

VMdPreview.use(vuepressTheme, {
    Prism,
});
VueMarkdownEditor.lang.use("en-US", enUS);
Vue.use(VueMarkdownEditor);
Vue.use(VMdPreview);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const opts = {};

const app = new Vue({
    el: "#app",
    store,
    router,
    vuetify: new Vuetify(opts),
    components: {
        App,
    },
});
