<template>
    <div>
        <h1>プロフィール</h1>
        <v-form v-model="valid" class="white pa-7">
            <div class="mb-5 icon-size">
                <v-img
                    :src="$store.state.user.icon"
                    class="rounded-xl mb-4"
                >
                </v-img>
                <v-btn color="red white--text mb-3" @click="deleteProfileIcon"
                    >現在のアイコンを消去</v-btn
                >
                <v-file-input
                    accept="image/*"
                    label="アイコンを変更"
                    v-model="icon"
                    prepend-icon="mdi-camera"
                    filled
                ></v-file-input>
            </div>

            <v-textarea
                label="経歴"
                v-model="career"
                :rules="careerRules"
                :counter="1000"
                auto-grow
            ></v-textarea>

            <v-text-field
                label="プロフィールタイトル"
                v-model="title"
                :rules="textFieldRules"
                :counter="255"
            ></v-text-field>

            <v-textarea
                label="プロフィールの内容"
                v-model="text"
                :counter="10000"
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
                placeholder="@以降のユーザー名"
                :rules="textFieldRules"
                :counter="255"
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
            // フォームの制御
            valid: false,
            // フォームに入る値
            icon: null,
            career: "",
            title: "",
            text: "",
            email: "",
            twitter: "",
            // 入力値のバリデーション
            careerRules: [
                (value) =>
                    value.length <= 1000 || "最大文字数は文字数が1000字です",
            ],
            textAreaRules: [
                (value) =>
                    value.length <= 10000 || "最大文字数は文字数が10000字です",
            ],
            textFieldRules: [
                (value) =>
                    value.length <= 255 || "最大文字数は文字数が255字です",
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
                "User-Id": this.$store.state.user.id,
                "User-Name": this.$store.state.user.name,
            };
            axios
                .post("/profile/" + this.$store.state.user.id, data, {
                    headers,
                })
                .then(function (response) {
                    if (response.data === "Success!") {
                        location.reload();
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

        // ユーザーのプロフィールの取得
        async getProfile() {
            const profile = await axios
                .get("/user/" + this.$store.state.user.id + "/profile")
                .then(function (response) {
                    return response.data;
                })
                .catch(function (error) {
                    alert(
                        "サーバー側の問題により、プロフィールの取得が行えません。問題が解決するまでお待ちください。"
                    );
                    return;
                });

            // 表示するアイコンのURLの取得、なければデフォルト画像のURLを挿入
            const iconPath =
                profile.icon != null
                    ? profile.icon
                    : "https://whi.s3.amazonaws.com/asset/FogMan.png";
            this.$store.commit("setUserIcon", iconPath);
            this.career = profile.career ?? "";
            this.title = profile.title ?? "";
            this.text = profile.text ?? "";
            this.email = profile.mail ?? "";
            this.twitter = profile.twitter ?? "";
        },

        // アイコンの削除
        deleteProfileIcon() {
            const headers = {
                "User-Id": this.$store.state.user.id,
                "User-Name": this.$store.state.user.name,
            };
            axios
                .delete("/profile/" + this.$store.state.user.id, {
                    data: { name: this.$store.state.user.name },
                    headers,
                })
                .then(function (response) {
                    location.reload();
                    return;
                })
                .catch(function (error) {
                    alert(
                        "サーバー側の問題により、アイコンの削除が行えません。問題が解決するまでお待ちください。"
                    );
                });
        },
    },
    // プロフィール情報の取得
    mounted() {
        this.getProfile();
    },
};
</script>
<style scoped>
/* 画面幅ごとにアイコン表示のサイズを変更 */
@media (min-width: 700px) {
    .icon-size {
        width: 20%;
    }
}
@media (max-width: 699px) {
    .icon-size {
        width: 90%;
    }
}
</style>
