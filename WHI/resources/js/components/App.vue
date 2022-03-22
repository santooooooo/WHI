<template>
    <v-app style="background-color: black">
        <div class="white--text">
            <v-app-bar class="black text-h3 pa-6 ma-3" dark>
                <router-link to="/" class="text-decoration-none white--text">
                    <div class="header-font font-weight-bold">WHI?</div>
                </router-link>
                <v-spacer></v-spacer>

                <v-btn @click.stop="drawer = !drawer">Menu</v-btn>
            </v-app-bar>

            <v-navigation-drawer
                v-model="drawer"
                absolute
                temporary
                right
                width="12rem"
                style="background-color: rgba(100, 100, 100, 0.6)"
            >
                <v-list-item>
                    <v-btn :disabled="$store.state.user.id !== null">
                        <router-link
                            to="signup"
                            class="text-decoration-none black--text"
                        >
                            <v-list-item-title class="subtitle-1 pa-5">
                                新規登録
                            </v-list-item-title>
                        </router-link>
                    </v-btn>
                </v-list-item>

                <v-list-item>
                    <v-btn :disabled="$store.state.user.id !== null">
                        <router-link
                            to="login"
                            class="text-decoration-none black--text"
                        >
                            <v-list-item-title class="subtitle-1 pa-5">
                                ログイン
                            </v-list-item-title>
                        </router-link>
                    </v-btn>
                </v-list-item>

                <v-list-item>
                    <v-btn
                        :disabled="$store.state.user.id === null"
                        @click="goMypage"
                    >
                        <v-list-item-title class="subtitle-1 pa-5">
                            マイページ
                        </v-list-item-title>
                    </v-btn>
                </v-list-item>

                <v-list-item>
                    <v-btn
                        :disabled="$store.state.user.id === null"
                        @click="logout"
                    >
                        <v-list-item-title class="subtitle-1 pa-5">
                            ログアウト
                        </v-list-item-title>
                    </v-btn>
                </v-list-item>

                <v-list-item>
                    <v-btn
                        :disabled="$store.state.user.id === null"
                        @click="check"
                    >
                        <v-list-item-title class="subtitle-1 pa-5">
                            退会
                        </v-list-item-title>
                    </v-btn>
                </v-list-item>
            </v-navigation-drawer>

            <v-overlay :absolute="false" :value="overlay">
                <v-row justify="center" align="center">
                    <v-alert
                        color="yellow darken-3 white--text"
                        border="top"
                        dark
                    >
                        今までのデータがすべて消えてしまいますが、よろしいでしょうか？
                        <v-list-item class="justify-center">
                            <v-btn @click="userCancel" class="ma-3 red">
                                <v-list-item-title class="subtitle-1 pa-5">
                                    退会する
                                </v-list-item-title>
                            </v-btn>

                            <v-btn @click="check" class="ma-3 green">
                                <v-list-item-title class="subtitle-1 pa-5">
                                    戻る
                                </v-list-item-title>
                            </v-btn>
                        </v-list-item>
                    </v-alert>
                </v-row>
            </v-overlay>

            <router-view></router-view>
        </div>
    </v-app>
</template>

<script>
export default {
    data() {
        return {
            drawer: false, //メニューの表示制御
            overlay: false,
        };
    },
    methods: {
        check() {
            this.drawer = !this.drawer;
            this.overlay = !this.overlay;
        },
        userCancel() {
            //　axios.post実行後に作成・取得したthisインスタンスではVuexの機能を使用できないため、ここでthisインスタンスを作成・取得
            const vm = this;
            const headers = {
                "User-Id": this.$store.state.user.id,
                "User-Name": this.$store.state.user.name,
            };
            axios
                .delete("/user/" + vm.$store.state.user.id, {
                    data: { name: vm.$store.state.user.name },
                    headers,
                })
                .then(function (response) {
                    if (response.data !== "error") {
                        vm.$store.commit("resetUserInfo");
                        vm.overlay = !vm.overlay;
                        vm.$router.push("/");
                        return;
                    }
                    return;
                })
                .catch(function (error) {
                    // サーバ側から何らかのエラーが発せられた場合
                    alert(
                        "サーバー側の問題により、現在新規登録が行えません。問題の対処が完了するまでお待ちください。"
                    );
                });
        },
        logout() {
            this.$store.commit("resetUserInfo");
            this.$router.push("/");
        },
        goMypage() {
            this.$router.push("/mypage");
        },
    },
    mounted() {},
};
</script>

<style scoped>
.header-font {
    font-family: "Merriweather", serif;
}
</style>
