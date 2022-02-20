<template>
    <div>
        <h1>プロフィール</h1>

        <v-form v-model="valid" class="white pa-7" width="30%">
            <v-file-input
                accept="image/*"
                label="icon"
                v-model="icon"
                prepend-icon="mdi-camera"
                filled
            ></v-file-input>

            <v-textarea
                label="career"
                v-model="career"
                :rules="careerRules"
                auto-grow
            ></v-textarea>

            <v-text-field
                label="title"
                v-model="title"
                :rules="textFieldRules"
                auto-grow
            ></v-text-field>

            <v-textarea
                label="text"
                v-model="text"
                :rules="textAreaRules"
                auto-grow
            ></v-textarea>

            <v-text-field
                label="email"
                v-model="email"
                :rules="emailRules"
            ></v-text-field>

            <v-text-field
                label="twitter"
                v-model="twitter"
                :rules="textFieldRules"
            ></v-text-field>

            <v-btn
                color="green white--text"
                :disabled="!valid"
                @click="updateProfile"
                >プロフィールの更新</v-btn
            >
        </v-form>
    </div>
</template>

<script>
export default {
    data() {
        return {
            valid: false,
            showAlert: false,
            icon: null,
            career: "",
            title: "",
            text: "",
            email: "",
            twitter: "",
            careerRules: [
                (value) => value.length <= 1000 || "最大文字数は文字数が1000字です",
            ],
            textAreaRules: [
                (value) => value.length <= 10000 || "最大文字数は文字数が10000字です",
            ],
            textFieldRules: [
                (value) => value.length <= 255 || "最大文字数は文字数が255字です",
            ],
            emailRules: [
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
        };
    },
    methods: {
        // ユーザーのプロフィールの情報の更新
        updateProfile() {
            //　axios.post実行後に作成・取得したthisインスタンスではVuexの機能を使用できないため、ここでthisインスタンスを作成・取得
            //const vm = this;

            let data = new FormData();
            data.append("name", this.$store.state.user.name);
            if (this.icon != null) {
                data.append("icon", this.icon);
            }
            data.append("career", this.career);
            data.append("title", this.title);
            data.append("text", this.text);
            data.append("email", this.email);
            data.append("twitter", this.twitter);
            const headers = {
                "Content-Type": "multipart/form-data",
                "X-HTTP-Method-Override": "PUT",
            };
            axios
                .post("/profile/" + this.$store.state.user.id, data, {
                    headers,
                })
                .then(function (response) {
                    if (response.data === "Success!") {
                        console.log("update profile success!");
                        //    window.location.reload();
                        return;
                    }
                    return;
                })
                .catch(function (error) {
                    // サーバ側から何らかのエラーが発せられた場合
                    alert(
                        "サーバー側の問題により、プロフィールの更新が行えません。問題が解決するまでお待ちください。"
                    );
                });
        },
    },
};
</script>
