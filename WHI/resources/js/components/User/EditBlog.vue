<template>
    <div class="white--text pa-5">
        <h1>ブログ作成フォーム</h1>
        <p>※PC推奨</p>

        <p v-if="updated !== null">更新日: {{ updated }}</p>
        <v-form v-model="valid" class="white mb-5 pa-3">
            <v-textarea
                label="タイトル"
                v-model="title"
                :counter="255"
                :rules="titleRules"
                auto-grow
                required
                type="string"
                rows="1"
            ></v-textarea>
        </v-form>

        <v-md-editor
            class="black--text"
            v-model="text"
            height="60rem"
            left-toolbar="undo redo clear | h bold italic strikethrough quote | ul ol table hr | link image code | save | tip"
        ></v-md-editor>

        <div class="d-flex justify-end">
            <v-btn
                v-if="Number($route.params.blogId) === 0"
                color="green"
                :disabled="!valid || text.length === 0"
                @click="create"
                class="mt-5 mb-50 white--text"
                >ブログの追加</v-btn
            >
            <v-btn
                v-if="Number($route.params.blogId) > 0"
                color="green"
                :disabled="!valid || text.length === 0"
                @click="update"
                class="mt-5 white--text"
                >ブログの更新</v-btn
            >
        </div>
        <v-overlay :absolute="false" :value="overlay">
            <v-row justify="center" align="center">
                <v-alert color="yellow darken-3 white--text" border="top" dark>
                    <p v-if="Number($route.params.blogId) === 0">
                        ブログの作成に成功しました
                    </p>
                    <p v-if="Number($route.params.blogId) > 0">
                        ブログの更新に成功しました
                    </p>
                    <v-list-item class="justify-center">
                        <v-btn @click="userPage" class="ma-3 red">
                            <v-list-item-title class="subtitle-1 pa-5">
                                ユーザーページへ行く
                            </v-list-item-title>
                        </v-btn>

                        <v-btn @click="checkBlog" class="ma-3 blue">
                            <v-list-item-title class="subtitle-1 pa-5">
                                ブログを確認する
                            </v-list-item-title>
                        </v-btn>

                        <v-btn @click="back" class="ma-3 green">
                            <v-list-item-title class="subtitle-1 pa-5">
                                戻る
                            </v-list-item-title>
                        </v-btn>
                    </v-list-item>
                </v-alert>
            </v-row>
        </v-overlay>
    </div>
</template>

<script>
export default {
    data() {
        return {
            sectionId: null,
            blogId: null,
            valid: true,
            title: null,
            text: "",
            updated: null,
            titleRules: [
                (value) => !!value || "何も入力されていません",
                (value) => {
                    if (value != null) {
                        return (
                            value.length <= 255 ||
                            "最大文字数は文字数が255字です"
                        );
                    }
                    return true;
                },
            ],
            overlay: false,
        };
    },
    methods: {
        create() {
            // ブログの新規作成が妥当であるかチェック
            const blogId = Number(this.blogId);
            if (blogId !== 0) {
                return;
            }

            //　axios.post実行後にthisインスタンスは使用できないため、ここでthisインスタンスを作成・取得
            const vm = this;
            const data = {
                userId: this.$store.state.user.id,
                sectionId: Number(this.sectionId),
                title: this.title,
                text: this.text,
            };
            const headers = {
                "User-Id": this.$store.state.user.id,
                "User-Name": this.$store.state.user.name,
            };
            axios
                .post("/blog/", data, { headers })
                .then(function (response) {
                    if (response.data === "Success") {
                        vm.overlay = true;
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
        // ブログデータを取得
        getBlog() {
            if (this.blogId === 0) {
                return;
            }
            const vm = this;
            axios
                .get("/blog/" + this.blogId)
                .then(function (response) {
                    if (response.data !== null) {
                        const blog = response.data;
                        vm.title = blog["title"];
                        vm.text = blog["text"];
                        vm.updated = blog["updated"];
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
        update() {
            console.log("update");
        },
        userPage() {
            this.$router.push("/mypage/");
        },
        checkBlog() {
            console.log("Have not finished yet");
        },
        back() {
            this.overlay = false;
        },
    },
    computed: {},
    mounted() {
        // アクセス先がユーザーではない場合は、トップページへ移動させる
        if (
            this.$store.state.user.id !== null &&
            this.$store.state.user.name !== null
        ) {
            this.sectionId = this.$route.params.sectionId;
            this.blogId = this.$route.params.blogId;
            if (this.blogId > 0) {
                this.getBlog();
            }
            return;
        }
        return this.$router.push("/");
    },
};
</script>
