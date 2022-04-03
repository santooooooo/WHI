<template>
    <v-container>
        <h1>パスワードを忘れた方へ</h1>
        <v-alert v-if="success" color="green lighten-1" border="top" dark
            >メールの送信に成功しました。（メールの送信には数分かかります）</v-alert
        >

        <v-form v-model="valid" class="white pa-7" width="30%">
            <v-text-field
                required
                label="email"
                v-model="email"
                :rules="emailRules"
            ></v-text-field>
            <v-btn
                color="green white--text"
                :disabled="!valid"
                @click="sendEmail"
                >メールを送信</v-btn
            >
        </v-form>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            // フォームの値
            email: "",
            // フォームのバリデーションに使用
            valid: false,
            emailRules: [
                (v) => !!v || "メールが入力されていません",
                (v) => /.+@.+\..+/.test(v) || "メールアドレスとして無効です",
            ],
            // フォームの結果の表示に使用
            success: false,
        };
    },
    methods: {
        // パスワード再設定用のURLが記載されているメールを送信
        sendEmail() {
            this.valid = !this.valid;
            //　axios.post実行後に作成・取得したthisインスタンスではVuexの機能を使用できないため、ここでthisインスタンスを作成・取得
            const vm = this;
            axios
                .post("/sendEmail", {
                    email: this.email,
                })
                .then(function (response) {
                    if (response.data === "Success") {
                        //既に使用されているメールアドレスからの登録を行った場合
                        vm.success = !vm.success;
                        vm.valid = !vm.valid;
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
    },
};
</script>
