<template>
    <v-container>
        <h1>パスワードリセットフォーム</h1>
        <v-alert v-if="idAlert" color="red lighten-1" border="top" dark
            >IDが間違えているか、IDの有効時間が切れました。<a
                @click="forgetPasswordPage"
                >こちらのページ</a
            >からもう一度IDの取得を行ってください</v-alert
        >
        <v-alert v-if="passwordAlert" color="red lighten-1" border="top" dark
            >パスワードと確認用パスワードの入力情報が異なります。もう一度入力しなおしてください。</v-alert
        >
        <v-alert v-if="userAlert" color="red lighten-1" border="top" dark
            >このメールアドレスを使用するユーザーは現在存在しません。<a
                @click="forgetPasswordPage"
                >こちらのページ</a
            >から登録しているメールアドレスでパスワードの再設定を行ってください。</v-alert
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
                label="パスワード(英数字と@%!$から6文字以上)"
                v-model="password"
                :rules="passwordRules"
                type="password"
            ></v-text-field>
            <v-text-field
                required
                label="確認用パスワード(英数字と@%!$から6文字以上)"
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
            idAlert: false,
            identification: "",
            email: null,
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
            passwordAlert: false,
            userAlert: false,
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
                    vm.idAlert = !vm.idAlert;
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
            //　axios.post実行後に作成・取得したthisインスタンスではVuexの機能を使用できないため、ここでthisインスタンスを作成・取得
            const vm = this;
            if (this.password !== this.passwordCheck) {
                //入力したパスワードと確認用のパスワードが異なる場合
                vm.passwordAlert = !vm.passwordAlert;
                return;
            }
            axios
                .post("/resetPassword", {
                    email: this.email,
                    password: this.password,
                })
                .then(function (response) {
                    if (response.data === "Success") {
                        vm.$router.push("/login");
                        return;
                    }
                    // メールアドレスに該当するユーザーがいない場合
                    vm.userAlert = !vm.userAlert;
                    return;
                })
                .catch(function (error) {
                    // サーバ側から何らかのエラーが発せられた場合
                    alert(
                        "サーバー側の問題により、現在新規登録が行えません。問題の対処が完了するまでお待ちください。"
                    );
                });
        },
        // パスワードを忘れた方用のページ
        forgetPasswordPage() {
            this.$router.push("/forget-password");
        },
    },
};
</script>
