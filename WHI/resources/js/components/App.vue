<template>
    <v-app>
        <div class="black white--text">
            <v-app-bar class="black text-h3 pa-6 ma-3" dark>
                <router-link to="/" class="text-decoration-none white--text">
                    <div class="header-font font-weight-bold">WHI?</div>
                </router-link>
                <v-spacer></v-spacer>

                <v-btn @click.stop="drawer = !drawer"
                    ><v-icon size="2.5rem">mdi-menu-open</v-icon></v-btn
                >
            </v-app-bar>

            <v-navigation-drawer v-model="drawer" absolute temporary right width="9rem">
                <v-list nav dense>
                    <v-list-item v-for="menu in menus" :key="menu.key">
                        <router-link
                            :to="menu.url"
                            class="text-decoration-none black--text"
                        >
                            <v-list-item-title class="subtitle-1 pa-5">
                                {{ menu.name }}
                            </v-list-item-title>
                        </router-link>
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
            drawer: false,
            group: null,
            menus: [],
        };
    },
    mounted() {
        this.menus[0] = { name: "新規登録", url: "aha" };
        this.menus[1] = { name: "ログイン", url: "aha" };
        this.menus[2] = { name: "ログアウト", url: "aha" };
        this.menus[3] = { name: "退会", url: "aha" };
        return;
    },
};
</script>

<style scoped>
.header-font {
    font-family: "Merriweather", serif;
}
.v-list-item:hover {
    color: white;
    background-color: grey;
}
</style>
