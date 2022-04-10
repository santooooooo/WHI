<template>
    <div class="white--text">
        <h1>{{ sectionName }}</h1>
        <v-divider class="grey"></v-divider>

        <v-menu offset-y>
            <template v-slot:activator="{ on, attrs }">
                <v-btn
                    color="primary"
                    dark
                    v-bind="attrs"
                    v-on="on"
                    :disabled="!addButton"
                    class="ma-3"
                >
                    コンテンツを追加する
                </v-btn>
            </template>
            <v-list>
                <v-list-item v-for="(item, index) in items" :key="index">
                    <v-list-item-title
                        style="cursor: pointer"
                        @click="openForm(item.title)"
                        >{{ item.title }}</v-list-item-title
                    >
                </v-list-item>
            </v-list>
        </v-menu>

        <v-form v-model="valid" class="white pa-3" v-if="form">
            <v-textarea
                :label="type"
                v-model="content"
                :counter="10000"
                :rules="contentRules"
                auto-grow
                required
                type="string"
                rows="3"
            ></v-textarea>

            <div class="d-flex">
                <v-btn
                    color="green white--text"
                    :disabled="!valid"
                    @click="create"
                    >コンテンツの追加</v-btn
                >
                <v-btn color="red white--text" @click="closeForm" class="ml-3"
                    >閉じる</v-btn
                >
            </div>
        </v-form>

        <h2 class="mt-2 ml-3">全てのコンテンツ</h2>
        <v-list-item
            v-for="content in contents"
            :key="content.id"
            class="ml-3 mt-2 mb-2 pt-3 white--text row"
            style="display: grid"
        >
            <v-list-item-title
                v-if="content.type === 'text'"
                max-width="100%"
                style="white-space: pre-line"
            >
                <div>
                    {{ content.substance }}
                </div>
            </v-list-item-title>
            <v-card
                :href="content.substance"
                v-if="content.type === 'url'"
                class="black--text ogp-pozition"
            >
                <v-img class="ogp-img" :src="ogpImage(content.id)"></v-img>
                <div>
                    <v-card-title>{{ ogpTitle(content.id) }} </v-card-title>
                    <v-card-text>{{ ogpDescription(content.id) }} </v-card-text>
                    <v-card-text>{{ content.substance }} </v-card-text>
                </div>
            </v-card>

            <v-card
                :href="content.substance"
                v-if="content.type === 'blog'"
                class="black--text ogp-pozition"
            >
                <v-img
                    class="ogp-img"
                    src="https://whi.s3.amazonaws.com/asset/BlogImage.png"
                ></v-img>
                <div>
                    <v-card-title>{{ blogTitle(content.id) }} </v-card-title>
                    <v-card-text>{{ content.substance }} </v-card-text>
                </div>
            </v-card>

            <div
                class="d-flex justify-end mt-2"
                style="box-sizing: inherit"
                v-if="content.type !== 'blog'"
            >
                <v-btn
                    v-if="!content.update"
                    @click="check(content.id, content.type, content.substance)"
                    class="ml-1 red white--text"
                    >削除</v-btn
                >
                <v-btn
                    v-if="!content.update"
                    @click="content.update = !content.update"
                    class="ml-1 blue white--text"
                    >変更</v-btn
                >
            </div>
            <div
                class="d-flex justify-end mt-2"
                style="box-sizing: inherit"
                v-else
            >
                <v-btn
                    @click="check(content.id, content.type, content.substance)"
                    class="ml-1 red white--text"
                    >削除</v-btn
                >
                <v-btn
                    @click="updateBlog(content.substance)"
                    class="ml-1 blue white--text"
                    >変更</v-btn
                >
            </div>

            <v-form v-if="content.update" v-model="valid" class="white pa-1">
                <v-textarea
                    required
                    label="変更内容"
                    :rules="contentRules"
                    :counter="10000"
                    v-model="updateSubstance"
                >
                </v-textarea>
                <v-btn
                    color="green white--text"
                    :disabled="!valid"
                    @click="update(content.id)"
                    >変更する</v-btn
                >
                <v-btn
                    color="red white--text"
                    @click="content.update = !content.update"
                    >やめる</v-btn
                >
            </v-form>
        </v-list-item>

        <v-overlay :absolute="false" :value="overlay">
            <v-row justify="center" align="center">
                <v-alert color="yellow darken-3 white--text" border="top" dark>
                    本当にこのコンテンツのデータを消去しますか？
                    <v-list-item class="justify-center">
                        <v-btn
                            @click="deleteContent()"
                            class="ma-3 red"
                            v-if="deleteType !== 'blog'"
                        >
                            <v-list-item-title class="subtitle-1 pa-5">
                                削除する
                            </v-list-item-title>
                        </v-btn>

                        <v-btn
                            @click="deleteContentAndBlog()"
                            class="ma-3 red"
                            v-else
                        >
                            <v-list-item-title class="subtitle-1 pa-5">
                                ブログを削除する
                            </v-list-item-title>
                        </v-btn>

                        <v-btn @click="check()" class="ma-3 green">
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
    props: {
        sectionId: {
            type: Number,
            required: true,
        },
        sectionName: {
            type: String,
            required: true,
        },
        contents: {
            type: Array,
            required: true,
        },
    },
    data() {
        return {
            // フォームのバリデーションに使用
            valid: true,
            contentRules: [
                (value) => !!value || "何も入力されていません",
                (value) => {
                    if (value != null) {
                        return (
                            value.length <= 10000 ||
                            "最大文字数は文字数が10000字です"
                        );
                    }
                    return true;
                },
            ],
            // コンテンツフォームの値
            content: null,
            type: null,
            // コンテンツフォームの制御に使用
            items: [{ title: "text" }, { title: "url" }, { title: "blog" }],
            form: false,
            addButton: true,
            // コンテンツの更新する値
            updateSubstance: null,
            // 全てのurlのOGPの値
            ogps: [],
            // 全てのblogの値
            blogs: [],
            // コンテンツ・ブログの削除に使用
            deleteContentId: null,
            deleteType: null,
            deleteBlogId: null,
            // 削除の際の確認表示の制御に使用
            drawer: false,
            overlay: false,
        };
    },
    methods: {
        // コンテンツフォームを開く
        openForm(type) {
            if ((type === "text") | (type === "url")) {
                this.type = type;
                this.addButton = !this.addButton;
                this.form = true;
                return;
            } else if (type === "blog") {
                const newBlogId = 0;
                return this.$router.push(
                    "/sections/" + this.sectionId + "/edit-blog/" + newBlogId
                );
            }
            return;
        },
        // コンテンツフォームを閉じる
        closeForm() {
            this.addButton = !this.addButton;
            this.form = !this.form;
        },
        // 新たなコンテンツを作成
        create() {
            //　axios.post実行後にthisインスタンスは使用できないため、ここでthisインスタンスを作成・取得
            const vm = this;
            const data = {
                userId: this.$store.state.user.id,
                sectionId: this.sectionId,
                type: this.type,
                substance: this.content,
            };
            const headers = {
                "User-Id": this.$store.state.user.id,
                "User-Name": this.$store.state.user.name,
            };
            axios
                .post(
                    "/user/" + this.$store.state.user.id + "/contents",
                    data,
                    { headers }
                )
                .then(function (response) {
                    if (response.data !== null) {
                        const newContent = {
                            id: response.data["id"],
                            sectionId: response.data["section_id"],
                            type: response.data["type"],
                            substance: response.data["substance"],
                            update: false,
                        };
                        vm.contents.push(newContent);
                        vm.content = null;
                        vm.form = !vm.form;
                        vm.addButton = !vm.addButton;

                        vm.changeOgp();
                    }
                    return;
                })
                .catch(function (error) {
                    // サーバ側から何らかのエラーが発せられた場合
                    alert(
                        "サーバー側の問題により、コンテンツの作成が行えません。問題の対処が完了するまでお待ちください。"
                    );
                });
        },
        // コンテンツの削除を本当にするかどうかの確認
        check(id, type, substance) {
            // 変数の初期化
            this.deleteContentId = null;
            // 引数に値が渡された場合、それをコンテンツの削除の際に使用
            if (id != null) {
                this.deleteContentId = id;
                this.deleteType = type;
                this.deleteBlogId = substance.substring(substance.length - 1);
            }
            // 削除の確認の表示の制御
            this.drawer = !this.drawer;
            this.overlay = !this.overlay;
        },
        // コンテンツを更新
        update(contentId) {
            const vm = this;
            const data = {
                userId: this.$store.state.user.id,
                sectionId: this.sectionId,
                substance: this.updateSubstance,
            };
            const headers = {
                "User-Id": this.$store.state.user.id,
                "User-Name": this.$store.state.user.name,
            };
            axios
                .put("/contents/" + contentId, data, {
                    headers,
                })
                .then(function (response) {
                    // リクエストが正常に実行された際、元の項目名を新たな項目名へ書き換える
                    if (response.data !== "Error") {
                        vm.contents.forEach((element) => {
                            if (element.id === contentId) {
                                const index = vm.contents.indexOf(element);
                                const updateContent = {
                                    id: response.data["id"],
                                    sectionId: response.data["section_id"],
                                    type: response.data["type"],
                                    substance: response.data["substance"],
                                    update: false,
                                };
                                vm.contents.splice(index, 1, updateContent);
                            }
                        });
                        vm.changeOgp();
                    }
                    return;
                })
                .catch(function (error) {
                    // サーバ側から何らかのエラーが発せられた場合
                    alert(
                        "サーバー側の問題により、コンテンツの更新が行えません。問題の対処が完了するまでお待ちください。"
                    );
                });
        },
        // コンテンツを削除
        deleteContent() {
            const vm = this;
            const data = {
                userId: this.$store.state.user.id,
                sectionId: this.sectionId,
            };
            const headers = {
                "User-Id": this.$store.state.user.id,
                "User-Name": this.$store.state.user.name,
            };
            axios
                .delete("/contents/" + this.deleteContentId, {
                    data,
                    headers,
                })
                .then(function (response) {
                    vm.contents.forEach((element) => {
                        if (element.id == vm.deleteContentId) {
                            const index = vm.contents.indexOf(element);
                            vm.contents.splice(index, 1);
                        }
                    });
                    // 削除の確認表示の消去
                    vm.drawer = !vm.drawer;
                    vm.overlay = !vm.overlay;
                    // 項目の削除のデータを格納する変数を初期値へ戻す
                    vm.deleteContentId = null;
                    return;
                })
                .catch(function (error) {
                    // サーバ側から何らかのエラーが発せられた場合
                    alert(
                        "サーバー側の問題により、コンテンツの削除が行えません。問題の対処が完了するまでお待ちください。"
                    );
                });
        },
        // URLのOGPの取得
        getOgp(id, url) {
            //　axios.post実行後にthisインスタンスは使用できないため、ここでthisインスタンスを作成・取得
            const vm = this;
            const data = {
                url: url,
            };
            axios
                .post("/ogp/", data)
                .then(function (response) {
                    if (response.data !== "Error") {
                        const ogpInfo = response.data;
                        const newOgp = {
                            id: id,
                            title: ogpInfo["title"],
                            description: ogpInfo["description"],
                            image: ogpInfo["image"],
                        };
                        vm.ogps.push(newOgp);
                    }
                    return;
                })
                .catch(function (error) {
                    const newOgp = {
                        id: id,
                        title: "No data",
                        description: "No description",
                        image: null,
                    };
                    vm.ogps.push(newOgp);
                });
        },
        // 全てのURLのOGPから特定のIDのOGPのオブジェクトを取得
        getOgpObj(id) {
            let result = null;
            this.ogps.forEach((ogp) => {
                if (ogp.id === id) {
                    result = ogp;
                }
            });
            return result;
        },
        // セクションごとのURLの取得するOGPの切り替え
        changeOgp() {
            this.contents.forEach((content) => {
                if (content.type === "url") {
                    this.getOgp(content.id, content.substance);
                }
            });
        },
        //セクションごとのブログの情報の切り替え
        changeBlog() {
            this.contents.forEach((content) => {
                if (content.type === "blog") {
                    this.getBlog(content.id, content.substance);
                }
            });
        },
        // ブログの更新ページへ飛ばす
        updateBlog(substance) {
            const blogId = substance.substring(substance.length - 1);
            return this.$router.push(
                "/sections/" + this.sectionId + "/edit-blog/" + blogId
            );
        },
        // ブログとそのコンテンツの削除
        deleteContentAndBlog() {
            const vm = this;
            const data = {
                userId: this.$store.state.user.id,
                sectionId: this.sectionId,
            };
            const headers = {
                "User-Id": this.$store.state.user.id,
                "User-Name": this.$store.state.user.name,
            };
            axios
                .delete("/blog/" + this.deleteBlogId, {
                    data,
                    headers,
                })
                .then(function (response) {
                    vm.contents.forEach((element) => {
                        if (element.id == vm.deleteContentId) {
                            const index = vm.contents.indexOf(element);
                            vm.contents.splice(index, 1);
                        }
                    });
                    // 削除の確認表示の消去
                    vm.drawer = !vm.drawer;
                    vm.overlay = !vm.overlay;
                    // 項目の削除のデータを格納する変数を初期値へ戻す
                    vm.deleteContentId = null;
                    vm.deleteType = null;
                    vm.deleteBlogId = null;
                    return;
                })
                .catch(function (error) {
                    // サーバ側から何らかのエラーが発せられた場合
                    alert(
                        "サーバー側の問題により、ブログの削除が行えません。問題の対処が完了するまでお待ちください。"
                    );
                });
        },
        // ブログ情報の取得
        getBlog(contentId, url) {
            const vm = this;
            const blogId = url.substring(url.length - 1);
            axios
                .get("/blog/" + blogId)
                .then(function (response) {
                    if (response.data !== null) {
                        const blog = response.data;
                        const newBlog = {
                            id: contentId,
                            title: blog["title"],
                        };
                        vm.blogs.push(newBlog);
                    }
                    return;
                })
                .catch(function (error) {
                    // サーバ側から何らかのエラーが発せられた場合
                    alert(
                        "サーバー側の問題により、ブログの取得が行えません。問題の対処が完了するまでお待ちください。"
                    );
                });
        },
        // 全てのブログから特定のIDのブログのオブジェクトを取得
        getBlogObj(id) {
            let result = null;
            this.blogs.forEach((blog) => {
                if (blog.id === id) {
                    result = blog;
                }
            });
            return result;
        },
    },
    // セクションごとのURLの取得するOGPとブログ情報の切り替え
    mounted() {
        this.changeOgp();
        this.changeBlog();
    },
    computed: {
        // OGPのタイトル表示
        ogpTitle() {
            return function (id) {
                const ogp = this.getOgpObj(id);
                if (ogp === null) {
                    return null;
                }
                return ogp.title;
            };
        },
        // OGPのdescription表示
        ogpDescription() {
            return function (id) {
                const ogp = this.getOgpObj(id);
                if (ogp === null) {
                    return null;
                }
                return ogp.description;
            };
        },
        // OGPの画像表示
        ogpImage() {
            return function (id) {
                const ogp = this.getOgpObj(id);
                if (ogp === null) {
                    return null;
                }
                return ogp.image;
            };
        },
        // ブログのタイトル表示
        blogTitle() {
            return function (id) {
                const blog = this.getBlogObj(id);
                if (blog === null) {
                    return null;
                }
                return blog.title;
            };
        },
    },
};
</script>
<style scoped>
/* ogpの表示が大きくなってもボタンの位置を画面外に出さないようにする */
* {
    overflow-wrap: break-word;
    word-wrap: break-word;
}
.row {
    display: flex;
    flex-direction: row;
}
.row > * {
    min-width: 0;
}

/* 画面幅ごとにOGPの表示を変更 */
@media (min-width: 800px) {
    .ogp-pozition {
        display: flex;
    }
    .ogp-img {
        max-width: 50%;
    }
}
@media (max-width: 799px) {
    .ogp-img {
        max-width: 100%;
    }
}
</style>
