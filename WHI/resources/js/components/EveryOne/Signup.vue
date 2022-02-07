<template>
    <v-container>
        <v-form v-model="valid" class="white pa-6" width="30%">
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
            ></v-text-field>
            <v-btn :disabled="!valid" @click="signUp">登録</v-btn>
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
                (v) => !!v || "メールが入力されていません",
                (v) => /.+@.+\..+/.test(v) || "メールアドレスとして無効です",
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
        signUp() {
            axios
                .post("/user", {
                    name: this.name,
                    email: this.email,
                    password: this.password,
                })
                .then(function (response) {
                    console.log(response);
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
    },
};
</script>
