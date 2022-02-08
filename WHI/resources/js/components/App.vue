<template>
    <v-app style="background-color: black">
        <div class="white--text">
            <v-app-bar class="black text-h3 pa-6 ma-3" dark>
                <router-link to="/" class="text-decoration-none white--text">
                    <div class="header-font font-weight-bold">WHI?</div>
                </router-link>
                <v-spacer></v-spacer>

                <v-btn @click.stop="drawer = !drawer"
                    ><v-icon size="2.5rem">mdi-menu-open</v-icon></v-btn
                >
            </v-app-bar>

            <v-navigation-drawer
                v-model="drawer"
                absolute
                temporary
                right
                width="12rem"
                style="background-color: rgba(100, 100, 100, 0.6)"
            >
                <v-list nav dense>
                    <v-list-item v-for="menu in menus" :key="menu.key">
                        <v-btn :disabled="isUser(menu.isUser)" value="menu.isUser">
                            <router-link
                                :to="menu.url"
                                class="text-decoration-none black--text"
                            >
                                <v-list-item-title class="subtitle-1 pa-5">
                                    {{ menu.name }}
                                </v-list-item-title>
                            </router-link>
                        </v-btn>
                    </v-list-item>
                </v-list>
            </v-navigation-drawer>

            <router-view></router-view>
        </div>
    </v-app>
</template>

<script>
export default {
    data() {
        return {
            user: null, //アクセス元のチェック
            drawer: false, //メニューの表示制御
            menus: [
                { name: "新規登録", url: "signup", isUser: false },
                { name: "ログイン", url: "login", isUser: false },
                { name: "ログアウト", url: "logout", isUser: true },
                { name: "退会", url: "resign", isUser: true },
            ], //メニュー一覧
        };
    },
    computed: {
        isUser: function () {
            return function (isUser) {
                if (this.$store.state.user.name !== null) {
                    return !isUser;
                }
                return isUser;
            };
        },
    },
    mounted() {
    },
};
</script>

<style scoped>
.header-font {
    font-family: "Merriweather", serif;
}
</style>
