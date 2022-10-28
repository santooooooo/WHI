<template>
    <div>
        <h1>更新フォーム</h1>

        <v-alert
            v-if="showAlert == 'password wrong'"
            color="red lighten-1"
            border="top"
            dark
            >パスワードが間違っています。</v-alert
        >

        <v-alert
            v-if="showAlert == 'double email'"
            color="red lighten-1"
            border="top"
            dark
            >こちらのメールアドレスは既に使用されています。他のメールアドレスをご使用ください</v-alert
        >
        <v-alert v-if="showAlert == 'success'" color="success" border="top" dark
            >ユーザー情報が更新されました</v-alert
        >

        <v-form v-model="valid" class="white pa-7" width="30%">
            <v-text-field
                required
                label="name"
                v-model="newName"
                :rules="newNameRules"
            ></v-text-field>
            <v-text-field
                required
                label="email"
                v-model="newEmail"
                :rules="newEmailRules"
            ></v-text-field>
            <v-text-field
                required
                label="password"
                v-model="newPassword"
                :rules="newPasswordRules"
                type="password"
            ></v-text-field>
            <v-text-field
                required
                label="現在まで使用しているpassword"
                v-model="password"
                :rules="passwordRules"
                type="password"
            ></v-text-field>
            <v-btn color="green white--text" :disabled="!valid" @click="update"
                >更新</v-btn
            >
        </v-form>
    </div>
</template>

<script>
export default {
    data() {
        return {
            // フォームの値
            newName: "",
            newEmail: "",
            newPassword: "",
            password: "",
            // フォームのバリデーション
            valid: false,
            newNameRules: [
                (value) =>
                    value.length <= 255 || "最大文字数は文字数が255字です",
            ],
            newEmailRules: [
                (value) => {
                    if (value != "") {
                        return (
                            /.+@.+\..+/.test(value) ||
                            "メールアドレスとして無効です"
                        );
                    }
                    return true;
                },
            ],
            newPasswordRules: [
                (value) => {
                    if (value != "") {
                        return (
                            (/.+\w+/.test(value) && value.length >= 6) ||
                            "パスワードは半角英数字6文字以上です"
                        );
                    }
                    return true;
                },
            ],
            passwordRules: [
                (value) => !!value || "パスワードが入力されていません",
                (value) =>
                    (/.+\w+/.test(value) && value.length >= 6) ||
                    "パスワードは半角英数字6文字以上です",
            ],
            // フォームの送信結果の表示を制御
            showAlert: "",
        };
    },
    methods: {
        // ユーザー情報の更新
        update() {
            //　axios.post実行後に作成・取得したthisインスタンスではVuexの機能を使用できないため、ここでthisインスタンスを作成・取得
            const vm = this;
            const headers = {
                "User-Id": this.$store.state.user.id,
                "User-Name": this.$store.state.user.name,
            };
            axios
                .put(
                    "/user/" + this.$store.state.user.id,
                    {
                        password: this.password,
                        newName: this.newName,
                        newEmail: this.newEmail,
                        newPassword: this.newPassword,
                    },
                    { headers }
                )
                .then(function (response) {
                    if (response.data == "password wrong") {
                        vm.showAlert = "password wrong";
                        return;
                    } else if (response.data == "double email") {
                        vm.showAlert = "double email";
                        return;
                    }
                    //既に使用されているメールアドレスからの登録を行った場合
                    vm.$store.commit("setUserInfo", response.data);
                    vm.showAlert = "success";
                    vm.password = "";
                    vm.newName = "";
                    vm.newEmail = "";
                    vm.newPassword = "";
                    return;
                })
                .catch(function (error) {
                    // サーバ側から何らかのエラーが発せられた場合
                    alert(
                        "サーバー側の問題により、ユーザー情報の更新が行えません。問題の対処が完了するまでお待ちください。"
                    );
                });
        },
    },
};
</script>
