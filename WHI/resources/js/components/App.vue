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
            >
                <v-list nav dense>
                    <v-list-item v-for="menu in menus" :key="menu.key">
                        <v-btn :disabled="menu.isUser" value="menu.isUser" text>
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
            user: null,
            drawer: false,
            menus: [],
        };
    },
    mounted() {
        this.$store.commit("getCsrfToken", document.cookie.substring(11));

        this.menus[0] = { name: "新規登録", url: "signup", isUser: false };
        this.menus[1] = { name: "ログイン", url: "login", isUser: false };
        this.menus[2] = { name: "ログアウト", url: "logout", isUser: true };
        this.menus[3] = { name: "退会", url: "resign", isUser: true };

        this.user = this.$store.state.user;
        if (this.user.name !== null) {
            this.menus.forEach((menu) => (menu.isUser = !menu.isUser));
        }
        return;
    },
};
</script>

<style scoped>
.header-font {
    font-family: "Merriweather", serif;
}
</style>
