<template>
    <v-container>
        <h1>パスワードリセットフォーム</h1>
        <v-alert v-if="showAlert" color="red lighten-1" border="top" dark
            >IDが間違えているか、IDの有効時間が切れました。<a @click="forgetPasswordPage"
                >こちらのページ</a
            >からもう一度IDの取得を行ってください</v-alert
        >

        <v-form
            v-model="valid"
            v-if="!passwordForm"
            class="white pa-7"
            width="30%"
        >
            <v-text-field
                required
                label="ID"
                v-model="identification"
                :rules="identificationRules"
            ></v-text-field>
            <v-btn color="green white--text" :disabled="!valid" @click="checkId"
                >送信</v-btn
            >
        </v-form>

        <v-form
            v-model="valid"
            v-if="passwordForm"
            class="white pa-7"
            width="30%"
        >
            <v-text-field
                required
                label="password"
                v-model="password"
                :rules="passwordRules"
                type="password"
            ></v-text-field>
            <v-text-field
                required
                label="password-check"
                v-model="passwordCheck"
                :rules="passwordRules"
                type="password"
            ></v-text-field>
            <v-btn
                color="green white--text"
                :disabled="!valid"
                @click="updatePassword"
                >パスワードの更新</v-btn
            >
        </v-form>
    </v-container>
</template>

<script>
export default {
    data() {
        return {
            valid: false,
            showAlert: false,
            identification: "",
            passwordForm: false,
            password: "",
            passwordCheck: "",
            identificationRules: [
                (value) => !!value || "IDが入力されていません",
                (value) =>
                    (/.+\w+/.test(value) && value.length === 10) ||
                    "IDは半角英数字10文字です",
            ],
            passwordRules: [
                (value) => !!value || "パスワードが入力されていません",
                (value) =>
                    (/.+\w+/.test(value) && value.length >= 6) ||
                    "パスワードは半角英数字6文字以上です",
            ],
        };
    },
    methods: {
        // パスワード再設定用のIDのチェック
        checkId() {
            //　axios.post実行後に作成・取得したthisインスタンスではVuexの機能を使用できないため、ここでthisインスタンスを作成・取得
            const vm = this;
            axios
                .post("/checkId", {
                    id: this.identification,
                })
                .then(function (response) {
                    if (response.data !== "Error") {
                        vm.email = response.data;
                        vm.passwordForm = !vm.passwordForm;
                        return;
                    }
                    //IDが違うか有効時間切れの場合
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
        // パスワードの変更
        updatePassword() {
            console.log("updatePassword!");
        },
        // パスワードを忘れた方用のページ
        forgetPasswordPage() {
            this.$router.push("/forget-password");
        },
    },
};
</script>
