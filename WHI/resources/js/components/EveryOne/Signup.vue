<template>
    <v-container>
        <h1>新規登録フォーム</h1>

        <v-alert v-if="showAlert" color="red lighten-1" border="top" dark
            >こちらのメールアドレスは既に使用されています。他のメールアドレスから登録してください。</v-alert
        >

        <v-form v-model="valid" class="white pa-7" width="30%">
            <v-text-field
                required
                label="name"
                v-model="name"
                :rules="nameRules"
            ></v-text-field>
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
            <v-btn color="green white--text" :disabled="!valid" @click="signUp"
                >登録</v-btn
            >
        </v-form>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            valid: false,
            name: "",
            email: "",
            password: "",
            nameRules: [(value) => !!value || "名前が入力されていません"],
            emailRules: [
                (value) => !!value || "メールが入力されていません",
                (value) =>
                    /.+@.+\..+/.test(value) || "メールアドレスとして無効です",
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
        // 新規ユーザーの登録処理
        signUp() {
            //　axios.post実行後に作成・取得したthisインスタンスではVuexの機能を使用できないため、ここでthisインスタンスを作成・取得
            const vm = this;
            axios
                .post("/user", {
                    name: this.name,
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
    mounted() {
        console.log(this.$store.state.user);
    },
};
</script>
