<template>
    <v-container>
        <h1>ログインフォーム</h1>

        <v-alert v-if="showAlert" color="red lighten-1" border="top" dark
            >ログインできませんでした。もう一度入力してください。</v-alert
        >

        <v-form v-model="valid" class="white pa-7" width="30%">
            <v-text-field
                required
                label="email"
                v-model="email"
                :rules="emailRules"
            ></v-text-field>
            <v-text-field
                required
                label="password"
                v-model="password"
                :rules="passwordRules"
                type="password"
            ></v-text-field>
            <v-btn color="green white--text" :disabled="!valid" @click="login"
                >ログイン</v-btn
            >
        </v-form>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            valid: false,
            email: "",
            password: "",
            emailRules: [
                (v) => !!v || "メールが入力されていません",
                (v) => /.+@.+\..+/.test(v) || "メールアドレスとして無効です",
            ],
            passwordRules: [
                (value) => !!value || "パスワードが入力されていません",
                (value) =>
                    (/.+\w+/.test(value) && value.length >= 6) ||
                    "パスワードは半角英数字6文字以上です",
            ],
            showAlert: false,
        };
    },
    methods: {
        // ユーザーログインの登録処理
        login() {
            //　axios.post実行後に作成・取得したthisインスタンスではVuexの機能を使用できないため、ここでthisインスタンスを作成・取得
            const vm = this;
            axios
                .post("/login", {
                    email: this.email,
                    password: this.password,
                })
                .then(function (response) {
                    if (response.data !== "error") {
                        vm.$store.commit("setUserInfo", response.data);
                        vm.$router.push("/mypage");
                        return;
                    }
                    //既に使用されているメールアドレスからの登録を行った場合
                    vm.showAlert = !vm.showAlert;
                    return;
                })
                .catch(function (error) {
                    // サーバ側から何らかのエラーが発せられた場合
                    alert(
                        "サーバー側の問題により、現在新規登録が行えません。問題の対処が完了するまでお待ちください。"
                    );
                });
        },
    },
};
</script>
